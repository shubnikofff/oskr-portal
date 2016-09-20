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
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->date = new \MongoDate(mktime(0, 0, 0, date("n"), date("j") + 1));
        $this->beginTime = \Yii::$app->params['vks.minTime'];
        $this->endTime = \Yii::$app->params['vks.maxTime'];
        $this->dateInput = \Yii::$app->formatter->asDate($this->date->sec, 'dd.MM.yyyy');
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
            'default' => ['status', 'topic', 'dateInput', 'beginTimeInput', 'endTimeInput', 'foreignOrganizations', 'rsoUploadedFiles', 'mode', 'equipment', 'audioRecord', 'participantsId', 'note'],
            self::SCENARIO_REFRESH_PARTICIPANTS => ['dateInput', 'beginTimeInput', 'endTimeInput', 'participantsId'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->dateInput = \Yii::$app->formatter->asDate($this->date->sec, 'dd.MM.yyyy');
        $this->beginTimeInput = $this->beginTimeString;
        $this->endTimeInput = $this->endTimeString;
        $this->foreignOrganizations = $this->rsoAgreement === self::RSO_AGREEMENT_NO_NEED ? 0 : 1;
    }


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['topic', 'dateInput', 'beginTimeInput', 'endTimeInput', 'mode', 'foreignOrganizations'], 'required'],

            ['dateInput', MongoDateValidator::className(), 'format' => 'dd.MM.yyyy',
                'min' => \Yii::$app->formatter->asDate(mktime(0, 0, 0), 'dd.MM.yyyy'),
                'max' => \Yii::$app->formatter->asDate(strtotime("+1 week"), 'dd.MM.yyyy'),
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
                $allowTimeStamp = $this->date->sec + ($this->beginTime - \Yii::$app->params['vks.allowRequestUpdateMinute']) * 60;
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

            ['audioRecord', 'boolean'],
            ['audioRecord', 'filter', 'filter' => function ($value) {
                return boolval($value);
            }],

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
                'mimeTypes' => ['image/*', 'application/pdf'],
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
        if (parent::beforeSave($insert)) {

            $this->setRsoAgreement($this->foreignOrganizations ? self::RSO_AGREEMENT_IN_PROCESS : self::RSO_AGREEMENT_NO_NEED);

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

            if ($insert) {

                $this->status = self::STATUS_OSKR_CONSIDERATION;
                foreach ($this->participants as $participant) {
                    if ($participant->ahuConfirmation) {
                        $this->status = self::STATUS_ROOMS_CONSIDIRATION;
                    }
                }
            } elseif (!\Yii::$app->user->can(SystemPermission::APPROVE_REQUEST) && $this->status !== self::STATUS_ROOMS_CONSIDIRATION) {
                $this->status = self::STATUS_OSKR_CONSIDERATION;
                $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            }
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

        if ($insert) {
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
        }
    }


}