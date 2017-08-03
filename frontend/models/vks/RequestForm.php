<?php
/**
 * teleport
 * Created: 21.12.15 11:06
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\vks;

use common\components\events\RequestStatusChangedEvent;
use common\components\events\RoomStatusChangedEvent;
use common\components\MinuteFormatter;
use common\rbac\SystemPermission;
use frontend\models\rso\File;
use frontend\models\rso\RsoNotificationStrategy;
use MongoDB\BSON\UTCDateTime;
use yii\mongodb\validators\MongoDateValidator;
use common\components\validators\MinuteValidator;
use yii\helpers\ArrayHelper;
use common\models\vks\Participant;
use yii\web\UploadedFile;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RequestForm
 */
class RequestForm extends Request
{
    const SCENARIO_REFRESH_PARTICIPANTS = 'refresh_participants';
    /**
     * @var string Begin time representation in form
     */
    public $beginTimeInput;
    /**
     * @var string End time representation in form
     */
    public $endTimeInput;
    /**
     * @var boolean
     */
    public $foreignOrganizations;
    /**
     * @var UploadedFile[]
     */
    public $rsoUploadedFiles;
    /**
     * @var array
     */
    private $_newBookings = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->date = new UTCDateTime(strtotime('tomorrow') * 1000);
        $this->beginTime = \Yii::$app->params['vks.minTime'];
        $this->endTime = \Yii::$app->params['vks.maxTime'];
        $this->dateInput = \Yii::$app->formatter->asDate($this->date->toDateTime(), 'php:d.m.Y');
        $this->beginTimeInput = MinuteFormatter::asString($this->beginTime);
        $this->endTimeInput = MinuteFormatter::asString($this->endTime);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            RequestLogBehavior::class
        ]);
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            'default' => ['status', 'topic', 'dateInput', 'beginTimeInput', 'endTimeInput', 'foreignOrganizations', 'rsoUploadedFiles', 'mode', 'equipment', 'participantsId', 'note'],
            self::SCENARIO_REFRESH_PARTICIPANTS => ['dateInput', 'beginTimeInput', 'endTimeInput', 'participantsId'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->dateInput = \Yii::$app->formatter->asDate($this->date->toDateTime(), 'php:d.m.Y');
        $this->beginTimeInput = $this->beginTimeString;
        $this->endTimeInput = $this->endTimeString;
        $this->foreignOrganizations = $this->rsoAgreement === self::RSO_AGREEMENT_NO_NEED ? 0 : 1;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'foreignOrganizations' => 'Участие иностранных организаций'
        ]);
    }


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['topic', 'dateInput', 'beginTimeInput', 'endTimeInput', 'mode', 'foreignOrganizations'], 'required'],

            ['dateInput', MongoDateValidator::className(), 'format' => 'dd.MM.yyyy',
                'min' => \Yii::$app->formatter->asDate(mktime(0, 0, 0), 'dd.MM.yyyy'),
                'max' => \Yii::$app->formatter->asDate(strtotime(\Yii::$app->user->can(SystemPermission::BOOK_FOR_THE_YEAR) ? "+1 year" : "+1 week"), 'dd.MM.yyyy'),
            ],

            ['beginTimeInput', MinuteValidator::className(),
                'min' => MinuteFormatter::asString(\Yii::$app->params['vks.minTime']),
                'max' => $this->endTimeInput,
                'minuteAttribute' => 'beginTime'
            ],
            ['beginTimeInput', 'compare', 'compareAttribute' => 'endTimeInput', 'operator' => '!=='],
            ['beginTimeInput', function ($attribute) {
                if (\Yii::$app->user->can(SystemPermission::APPROVE_REQUEST)) {
                    return;
                }
                $allowTimeStamp = $this->date->toDateTime()->getTimestamp() + ($this->beginTime - \Yii::$app->params['vks.allowRequestUpdateMinute']) * 60;
                $now = time() + 3 * 60 * 60;

                if ($now > $allowTimeStamp) {
                    $this->addError($attribute, "Должно быть не меньше 20 минут от текущего времени");
                }
            }],

            ['endTimeInput', MinuteValidator::className(),
                'min' => $this->beginTimeInput,
                'max' => MinuteFormatter::asString(\Yii::$app->params['vks.maxTime']),
                'minuteAttribute' => 'endTime'
            ],

            ['mode', 'in', 'range' => [self::MODE_WITH_VKS, self::MODE_WITHOUT_VKS]],
            ['mode', 'filter', 'filter' => function ($value) {
                return intval($value);
            }],

            ['participantsId', 'required', 'on' => 'default', 'message' => 'Необходимо выбрать участников'],
            ['participantsId', function ($attribute) {

                $value = $this->{$attribute};

                if ($this->mode === self::MODE_WITH_VKS && count($value) < 2) {
                    $this->addError($attribute, 'Количество участников должно быть не менее двух');
                    return;
                }
                if ($this->mode === self::MODE_WITHOUT_VKS && count($value) !== 1) {
                    $this->addError($attribute, 'Помещение для совещания должно быть только одно');
                    return;
                }

                $allParticipants = Participant::findAllByRequest($this);
                $allParticipantsId = ArrayHelper::getColumn($allParticipants, '_id');

                foreach ($value as $participant) {
                    $key = array_search($participant, $allParticipantsId);
                    if ($key === false) {
                        $this->addError($attribute, 'Участник не найден');
                        return;
                    } elseif ($allParticipants[$key]->isBusy) {
                        $busyParticipant = $allParticipants[$key];
                        $this->addError($attribute, "Участник '{$busyParticipant->name}' занят с " . MinuteFormatter::asString($busyParticipant->busyFrom) . " до " . MinuteFormatter::asString($busyParticipant->busyTo));
                    }
                }
            }, 'on' => 'default'],

            ['foreignOrganizations', 'boolean'],

            ['rsoUploadedFiles', 'file', 'skipOnEmpty' => false,
                'mimeTypes' => ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                'maxSize' => 3 * 1024 * 1024,
                'maxFiles' => 3,
                'when' => function ($model) {
                    return (bool)$model->foreignOrganizations && count($model->rsoFiles) == 0;
                }],

            [['note', 'equipment'], 'safe'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        $beginTime = date_create_from_format('d.m.Y H:i', $this->dateInput .' '. $this->beginTimeInput);
        if($this->conference && $beginTime->getTimestamp() > time()) {
            $this->destroyConference();
        }

        if (parent::beforeSave($insert)) {

            $this->setRsoAgreement($this->foreignOrganizations ? self::RSO_AGREEMENT_IN_PROCESS : self::RSO_AGREEMENT_NO_NEED, new RsoNotificationStrategy());

            $rsoFiles = $this->rsoFiles;
            foreach ($this->rsoUploadedFiles as $rsoUploadedFile) {
                $file = new File([
                    'filename' => $rsoUploadedFile->name,
                    'mimeType' => $rsoUploadedFile->type,
                    'file' => $rsoUploadedFile
                ]);

                if ($file->save()) {
                    $rsoFiles[] = [
                        'id' => $file->primaryKey,
                        'name' => $file->filename,
                    ];
                }
            }
            $this->rsoFiles = $rsoFiles;

            if ($this->getOldAttribute('date') != $this->date) {
                $this->number = self::generateNumber($this->date);
            }

            /*if ($insert) {

                $this->status = self::STATUS_OSKR_CONSIDERATION;
                foreach ($this->participants as $participant) {
                    if ($participant->ahuConfirmation) {
                        $this->status = self::STATUS_ROOMS_CONSIDIRATION;
                    }
                }
            } elseif ($this->status !== self::STATUS_ROOMS_CONSIDIRATION) {
                $this->status = self::STATUS_OSKR_CONSIDERATION;
                $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            }*/


            $newBookings = [];
            foreach ($this->participants as $participant) {

                if($insert || !$this->getRoomStatus($participant->_id)) {

                    $newBookings[] = [
                        'room' => $participant,
                        'status' => $participant->ahuConfirmation ? Participant::STATUS_CONSIDIRATION : Participant::STATUS_APPROVE
                    ];
                }
            }

            $newStatus = self::STATUS_OSKR_CONSIDERATION;
            foreach ($newBookings as $newBooking) {

                if($newBooking['status'] === Participant::STATUS_CONSIDIRATION) {
                    $newStatus = self::STATUS_ROOMS_CONSIDIRATION;
                    $newBooking['room']->trigger(Participant::EVENT_STATUS_CHANGED, new RoomStatusChangedEvent(['request' => $this, 'roomStatus' => $newBooking['status']]));
                }
            }

            if($this->status !== $newStatus) {
                $this->status = $newStatus;
                $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            }

            $this->_newBookings = $newBookings;

            return true;

        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /*if ($insert) {
            $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));

            foreach ($this->participants as $participant) {

                if ($participant->ahuConfirmation) {
                    $roomStatus = Participant::STATUS_CONSIDIRATION;
                    $participant->trigger(Participant::EVENT_STATUS_CHANGED, new RoomStatusChangedEvent(['request' => $this, 'roomStatus' => $roomStatus]));
                } else {
                    $roomStatus = Participant::STATUS_APPROVE;
                }
                $participant->writeLog($this, $roomStatus, true);
            }
        }*/

        foreach ($this->_newBookings as $newBooking) {

            $newBooking['room']->writeLog($this, $newBooking['status'], true);

        }

    }

}