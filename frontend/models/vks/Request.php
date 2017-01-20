<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:24
 */
namespace frontend\models\vks;

use common\components\events\RequestStatusChangedEvent;
use common\models\vks\DeployServer;
use common\models\vks\Participant;
use frontend\components\services\CancelMeetingGreenAtomNotifier;
use frontend\components\services\FutureMeetingGreenAtomNotifier;
use frontend\models\rso\File;
use frontend\models\rso\NotificationStrategy;
use frontend\models\rso\UserNotificationStrategy;
use yii\helpers\ArrayHelper;
use common\components\MinuteFormatter;
use yii\mongodb\Collection;
use yii\mongodb\validators\MongoDateValidator;
use yii\mongodb\validators\MongoIdValidator;

/**
 * Class Request представляет модель заявки на ВКС
 *
 * @package common\models
 *
 * @property int $number
 * @property string $topic
 * @property \MongoDate $date
 * @property int $beginTime
 * @property string beginTimeString
 * @property int $endTime
 * @property string endTimeString
 * @property int $mode
 * @property string $modeString
 * @property array $equipment
 * @property bool $audioRecord
 * @property \MongoId $deployServerId
 * @property DeployServer $deployServer
 * @property \MongoId[] $participantsId
 * @property array $roomsOnConsidiration
 * @property Participant[] $participants
 * @property array $participantNameList
 * @property array $participantShortNameList
 * @property string $cancellationReason
 * @property \MongoId $buildServerId
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

    const SCENARIO_APPROVE = 'approve';
    const SCENARIO_CANCEL = 'cancel';
    const SCENARIO_SET_DEPLOY_SERVER = 'set_deploy_server';

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
            'audioRecord',
            'deployServerId',
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
            self::SCENARIO_APPROVE => ['status'],
            self::SCENARIO_CANCEL => ['status', 'cancellationReason'],
            self::SCENARIO_SET_DEPLOY_SERVER => ['deployServerId']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [

            ['cancellationReason', 'required'],

            ['dateInput', MongoDateValidator::className(), 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'date'],

            ['deployServerId', 'default', 'value' => null],
            ['deployServerId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            ['deployServerId', 'exist', 'targetClass' => DeployServer::className(), 'targetAttribute' => '_id'],

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
                    $participantsValue[] = new \MongoId($participant);
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
            'audioRecord' => 'Аудиозапись',
            'deployServerId' => 'Сервер сборки',
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


    public function getDeployServer()
    {
        return $this->hasOne(DeployServer::className(), ['_id' => 'deployServerId']);
    }

    /**
     * Return the names of participants
     * @return array
     */
    public function getParticipantNameList()
    {
        $names = ArrayHelper::toArray($this->participants, [
            Participant::className() => ['name']
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
            Participant::className() => ['shortName']
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
            $notifyTime = $this->date->sec + ($this->beginTime - 60) * 60;
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
     * @param \MongoId $roomId
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    public function getRoomStatus(\MongoId $roomId)
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

    /**
     * @return string
     */
    public function getModeString()
    {
        switch ($this->mode) {
            case self::MODE_WITH_VKS:
                return 'В режиме ВКС';
            case self::MODE_WITHOUT_VKS:
                return 'Без ВКС';
            default:
                return '';
        }
    }

    public function setRsoAgreement($value, NotificationStrategy $notifyStrategy)
    {
        if ($this->rsoAgreement !== $value) {
            $this->rsoAgreement = $value;
            $notifyStrategy->notify($this);
        }
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

    public static function generateNumber(\MongoDate $date)
    {
        $max = self::getCollection()->find(['date' => $date], ['number' => 1])->sort(['number' => -1])->limit(1)->getNext();
        return isset($max['number']) ? $max['number'] + 1 : 100;
    }

}