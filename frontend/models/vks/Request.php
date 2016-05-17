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
use yii\helpers\ArrayHelper;
use common\components\MinuteFormatter;
use yii\mongodb\validators\MongoDateValidator;
use yii\mongodb\validators\MongoIdValidator;

/**
 * Class Request представляет модель заявки на ВКС
 *
 * @package common\models
 *
 * @property string $topic
 * @property \MongoDate $date
 * @property int $beginTime
 * @property string beginTimeString
 * @property int $endTime
 * @property string endTimeString
 * @property int $mode
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
 */
class Request extends \common\models\Request
{
    const STATUS_ROOMS_CONSIDIRATION = 4;
    
    const MODE_WITH_VKS = 0;
    const MODE_WITHOUT_VKS = 1;

    const SCENARIO_APPROVE = 'approve';
    const SCENARIO_CANCEL = 'cancel';
    const SCENARIO_SET_DEPLOY_SERVER = 'set_deploy_server';
    /**
     * @var Participant[] the participants of VKS
     */
    private $_participants;
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
            'topic',
            'date',
            'beginTime',
            'endTime',
            'mode',
            'equipment',
            'audioRecord',
            'deployServerId',
            'participantsId',
            'roomsOnConsidiration',
            'cancellationReason',
            'buildServerId',
            'note',
        ]);
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

    /**
     * @return DeployServer
     */
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

    /**
     * @return bool
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVE;
        if ($this->cancellationReason) {
            $this->cancellationReason = null;
        }
        if ($this->save()) {
            $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        $this->status = self::STATUS_CANCEL;
        if ($this->save()) {
            $this->trigger(self::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $this]));
            return true;
        }
        return false;
    }
}