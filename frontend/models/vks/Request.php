<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:24
 */
namespace frontend\models\vks;

use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use yii\helpers\ArrayHelper;
use yii\mongodb\Collection;
use yii\mongodb\Query;
use yii\mongodb\validators\MongoDateValidator;
use yii\mongodb\validators\MongoIdValidator;
use common\components\events\RequestStatusChangedEvent;
use common\models\vks\AudioRecordType;
use common\models\vks\MCU;
use common\models\vks\Participant;
use common\components\MinuteFormatter;
use frontend\components\services\CancelMeetingGreenAtomNotifier;
use frontend\components\services\FutureMeetingGreenAtomNotifier;
use frontend\models\rso\File;
use frontend\models\rso\NotificationStrategy;
use frontend\models\rso\UserNotificationStrategy;

/**
 * Class Request представляет модель заявки на ВКС
 *
 * @package common\models
 *
 * @property int $number
 * @property string $topic
 * @property UTCDateTime $date
 * @property int $beginTime
 * @property string beginTimeString
 * @property int $endTime
 * @property string endTimeString
 * @property int $mode
 * @property array $equipment
 * @property string $mcuId
 * @property MCU $mcu
 * @property string $audioRecordTypeId
 * @property AudioRecordType $audioRecordType
 * @property bool $isConferenceCreated
 * @property string $conferenceName
 * @property string $conferenceId
 * @property string $conferencePassword
 * @property ObjectID[] $participantsId
 * @property array $roomsOnConsidiration
 * @property Participant[] $participants
 * @property array $participantNameList
 * @property array $participantShortNameList
 * @property string $cancellationReason
 * @property ObjectID $buildServerId
 * @property string $note
 * @property array $log
 * @property string $rsoAgreement
 * @property array $rsoFiles
 */
class Request extends \common\models\Request
{
    const STATUS_ROOMS_CONSIDIRATION = 4;

    const MODE_WITH_VKS = 0;
    const MODE_WITHOUT_VKS = 1;

    const SCENARIO_CANCEL = 'cancel';
    const SCENARIO_DEPLOY_CONFERENCE = 'deploy_conference';

    const RSO_AGREEMENT_NO_NEED = "Нет необходимости";
    const RSO_AGREEMENT_IN_PROCESS = "В процессе";
    const RSO_AGREEMENT_APPROVED = "Одобрено";
    const RSO_AGREEMENT_REFUSED = "Отказано";

    /**
     * @var Participant[] the participants of VKS
     */
    private $_participants;
    /**
     * @var array
     */
    private $_roomsStatus;
    /**
     * @var string Date representation in form
     */
    public $dateInput;

    public static function collectionName()
    {
        return 'vks.request';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'number',
            'topic',
            'date',
            'beginTime',
            'endTime',
            'rsoAgreement',
            'rsoFiles',
            'mode',
            'equipment',
            'mcuId',
            'audioRecordTypeId',
            'conferenceId',
            'conferencePassword',
            'participantsId',
            'roomsOnConsidiration',
            'cancellationReason',
            'buildServerId',
            'note',
            'log'
        ]);
    }

    public function init()
    {
        parent::init();

        $this->rsoFiles = [];
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CANCEL => ['cancellationReason'],
            self::SCENARIO_DEPLOY_CONFERENCE => ['mcuId', 'audioRecordTypeId']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [

            [['cancellationReason', 'mcuId', 'audioRecordTypeId'], 'required'],

            ['dateInput', MongoDateValidator::class, 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'date'],

            ['mcuId', 'exist', 'targetClass' => MCU::class, 'targetAttribute' => '_id'],

            ['audioRecordTypeId', 'exist', 'targetClass' => AudioRecordType::class, 'targetAttribute' => '_id'],

            ['participantsId', 'checkParticipantsIdFormat'],
        ]);
    }

    public function checkParticipantsIdFormat($attribute)
    {
        $participants = $this->{$attribute};
        $errorMessage = "{attribute} имеет неверный формат.";
        $participantsValue = [];
        if (is_array($participants)) {
            $mongoIdValidator = new MongoIdValidator();
            foreach ($participants as $participant) {
                if ($mongoIdValidator->validate($participant)) {
                    $participantsValue[] = new ObjectID($participant);
                } else {
                    $this->addError($attribute, $errorMessage);
                    return;
                }
            }
            $this->{$attribute} = $participantsValue;
            return;
        }
        $this->addError($attribute, $errorMessage);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number' => 'Номер заявки',
            'topic' => 'Тема',
            'dateInput' => 'Дата',
            'date' => 'Дата',
            'beginTimeInput' => 'Время начала',
            'endTimeInput' => 'Время конца',
            'mode' => 'Режим совещания',
            'equipment' => 'Дополнительное оборудование',
            'mcuId' => 'MCU',
            'audioRecordTypeId' => 'Тип аудиозаписи',
            'participantsId' => 'Участники',
            'status' => 'Статус',
            'cancellationReason' => 'Причина отмены',
            'note' => 'Примечание',
            'rsoAgreement' => 'Согласование с РСО'
        ];
    }

    /**
     * @return Participant[]
     */
    public function getParticipants()
    {
        if (!$this->_participants) {
            $this->_participants = is_array($this->participantsId) ? Participant::find()->with('company')->where(['_id' => ['$in' => $this->participantsId]])->all() : [];
        }

        return $this->_participants;
    }

    /**
     * @return null|string
     */
    public function getStatusName()
    {
        return self::statusName($this->status);
    }

    /**
     * @param $status
     * @return null|string
     */
    public static function statusName($status)
    {
        $name = null;
        switch ($status) {
            case self::STATUS_CANCEL:
                $name = 'Отменено';
                break;
            case self::STATUS_APPROVE:
                $name = 'Согласовано';
                break;
            case self::STATUS_COMPLETE:
                $name = 'Выполнено';
                break;
            case self::STATUS_OSKR_CONSIDERATION:
                $name = 'На рассмотрении ОСКР';
                break;
            case self::STATUS_ROOMS_CONSIDIRATION:
                $name = 'Комнаты на рассмотрении';
                break;
        };
        return $name;
    }

    public function getMcu()
    {
        return $this->hasOne(MCU::class, ['_id' => 'mcuId']);
    }

    public function getAudioRecordType()
    {
        return $this->hasOne(AudioRecordType::class, ['_id' => 'audioRecordTypeId']);
    }

    /**
     * Return the names of participants
     * @return array
     */
    public function getParticipantNameList()
    {
        $names = ArrayHelper::toArray($this->participants, [
            Participant::class => ['name']
        ]);
        return ArrayHelper::getColumn($names, 'name');
    }

    /**
     * Return the short names of participants
     * @return array
     */
    public function getParticipantShortNameList()
    {
        $names = ArrayHelper::toArray($this->participants, [
            Participant::class => ['shortName']
        ]);
        return ArrayHelper::getColumn($names, 'shortName');
    }

    /**
     * @return string
     */
    public function getBeginTimeString()
    {
        return $this->beginTime ? MinuteFormatter::asString($this->beginTime) : '';
    }

    /**
     * @return string
     */
    public function getEndTimeString()
    {
        return $this->endTime ? MinuteFormatter::asString($this->endTime) : '';
    }

    public function approve()
    {
        if ($this->status === self::STATUS_APPROVE) {
            return true;
        }

        $this->status = self::STATUS_APPROVE;
        $this->cancellationReason = null;

        if ($this->save(false)) {
            $notifyTime = $this->date->toDateTime()->getTimestamp() + ($this->beginTime - 60) * 60;
            if ((time() + 3 * 60 * 60) > $notifyTime) {
                FutureMeetingGreenAtomNotifier::sendMail($this);
            }
            $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
        }
        return true;
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        $this->status = self::STATUS_CANCEL;
        if ($this->save()) {
            CancelMeetingGreenAtomNotifier::sendMail($this);
            $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            return true;
        }
        return false;
    }

    /**
     * @param ObjectID $roomId
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function getRoomStatus(ObjectID $roomId)
    {
        if ($this->_roomsStatus === null) {
            /** @var Collection $collection */
            $collection = \Yii::$app->get('mongodb')->getCollection(Participant::collectionName());
            $pipeline = [
                ['$unwind' => '$log'],
                ['$match' => ['log.requestId' => $this->_id]],
                ['$project' => ['status' => '$log.status']]
            ];

            $this->_roomsStatus = ArrayHelper::map($collection->aggregate($pipeline), function ($item) {
                return (string)$item['_id'];
            }, 'status');
        }
        return $this->_roomsStatus[(string)$roomId];
    }

    public function setRsoAgreement($value, NotificationStrategy $notifyStrategy)
    {
        if ($this->rsoAgreement !== $value) {
            $this->rsoAgreement = $value;
            $notifyStrategy->notify($this);
        }
    }

    public function getIsConferenceCreated()
    {
        return !empty($this->conferenceId);
    }

    /**
     * @return bool
     */
    public function rsoApprove()
    {
        $this->setRsoAgreement(self::RSO_AGREEMENT_APPROVED, new UserNotificationStrategy());
        return $this->save(false);
    }

    /**
     * @return bool
     */
    public function rsoRefuse()
    {
        $this->setRsoAgreement(self::RSO_AGREEMENT_REFUSED, new UserNotificationStrategy());
        return $this->save(false);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $fileIds = ArrayHelper::getColumn($this->rsoFiles, 'id');
            File::deleteAll(['_id' => ['$in' => $fileIds]]);
            return true;
        } else {
            return false;
        }
    }

    public static function generateNumber(UTCDateTime $date)
    {
        $result = self::find()->where(['date' => $date])->orderBy(['number' => SORT_DESC])->limit(1)->asArray()->all();
        $number = $result[0]['number'];
        return $number === null ? 100 : $number + 1;
    }

    public function getConferenceName()
    {
        return date('d-m-Y', $this->date->toDateTime()->getTimestamp()) . "_" . $this->number;
    }
}