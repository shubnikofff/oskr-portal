<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:24
 */

namespace frontend\models\vks;

use frontend\models\NotifyService;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\mongodb\Collection;
use yii\mongodb\validators\MongoDateValidator;
use yii\mongodb\validators\MongoIdValidator;
use common\models\vks\Participant;
use common\components\MinuteFormatter;
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
 * @property Conference $conference
 * @property string $isVim
 * @property array $equipment
 * @property ObjectID[] $participantsId
 * @property array $roomsOnConsidiration
 * @property Participant[] $participants
 * @property array $participantNameList
 * @property array $participantShortNameList
 * @property string $cancellationReason
 * @property string $note
 * @property array $log
 * @property string $rsoAgreement
 * @property array $rsoFiles
 * @property string $satisfaction
 */
class Request extends \common\models\Request
{
    const STATUS_ROOMS_CONSIDIRATION = 4;

    const MODE_WITH_VKS = 0;
    const MODE_WITHOUT_VKS = 1;

    const SCENARIO_CANCEL = 'cancel';
    const SCENARIO_FEEDBACK = 'feedback';
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
            'conference',
            'equipment',
            'isVim',
            'participantsId',
            'roomsOnConsidiration',
            'cancellationReason',
            'note',
            'log',
            'satisfaction',
            'feedback'
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
            self::SCENARIO_FEEDBACK => ['satisfaction', 'feedback'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [

            [['cancellationReason', 'satisfaction'], 'required'],

            ['dateInput', MongoDateValidator::class, 'format' => 'dd.MM.yyyy', 'mongoDateAttribute' => 'date'],

            ['participantsId', 'checkParticipantsIdFormat'],

            ['satisfaction', 'integer', 'min' => 1, 'max' => 10],

            ['feedback', 'required', 'when' => function ($model) {
                return $model->satisfaction !== '10';
            }, 'message' => 'Пожалуйста укажите Ваши замечания']
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
            'isVim' => 'Очень важное совещание',
            'participantsId' => 'Участники',
            'status' => 'Статус',
            'cancellationReason' => 'Причина отмены',
            'note' => 'Примечание',
            'rsoAgreement' => 'Согласование с РСО',
            'satisfaction' => 'Оценка',
            'feedback' => 'Отзыв'
        ];
    }

    /**
     * @return Participant[]
     */
    public function getParticipants()
    {
        if (!$this->_participants) {
            $this->_participants = is_array($this->participantsId) ? Participant::find()->with('company', 'confirmPerson')->where(['_id' => ['$in' => $this->participantsId]])->all() : [];
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
            case self::STATUS_CANCELED:
                $name = 'Отменено';
                break;
            case self::STATUS_APPROVED:
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


    public function getEndTimeString()
    {
        return $this->endTime ? MinuteFormatter::asString($this->endTime) : '';
    }

    /**
     * @return bool
     */
    public function approve()
    {
        if ($this->status !== self::STATUS_APPROVED) {
            $this->status = self::STATUS_APPROVED;
            try {
                NotifyService::notifyOwnerAboutApprovedRequest($this);
                NotifyService::notifySupportAboutApprovedRequest($this);
            } catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        if ($this->status !== self::STATUS_CANCELED && $this->validate()) {
            $this->status = self::STATUS_CANCELED;
            if ($this->conference) {
                $this->destroyConference();
            }
            try {
                NotifyService::notifyOwnerAboutCanceledRequest($this);
                NotifyService::notifySupportAboutCanceledRequest($this);
            } catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
            return true;
        }
        return false;
    }

    /**
     * @param ConferenceForm $form
     * @return bool
     */
    public function createConference(ConferenceForm $form)
    {
        if ($form->validate()) {
            try {
                $result = ConferenceService::instance()->create($this, $form)->getData();
                if ($result['retcode'] === 100) {
                    $raw = $result['Conferences'][0];
                    if (!$raw['numericId']) {
                        throw new ErrorException('Конференция возможно не создалась. Код ответа от ' . \Yii::$app->params['mcugw.url'] . ' успешный, но данные о конференции не получены.');
                    }
                    $this->conference = new Conference($raw['conferenceName'], $raw['numericId'], $raw['pin'], $raw['mcuid'], $raw['profile'], $raw['recordType'], $result['extDS'], $result['intDS']);
                    return true;
                } else {
                    \Yii::$app->session->setFlash('danger', $result['errorMessage']);
                }
            } catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
        } else {
            \Yii::$app->session->setFlash('danger', Html::errorSummary($this));
        }
        return false;
    }

    /**
     * @return bool
     */
    public function destroyConference()
    {
        if ($this->conference) {
            try {
                $result = ConferenceService::instance()->destroy($this->conference)->getData();
                if ($result['retcode'] === 100) {
                    $this->conference = null;
                    return true;
                } else {
                    \Yii::$app->session->setFlash('danger', $result['errorMessage']);
                }
            } catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
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

    public function afterFind()
    {
        parent::afterFind();
        if ($row = $this->conference) {
            $conference = new Conference($row['name'], $row['number'], $row['password'], $row['mcuId'], $row['profileId'], $row['audioRecordTypeId'], $row['externalDS'], $row['internalDS']);
            $this->conference = $conference;
        }
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($conference = $this->conference) {
                $this->conference = [
                    'name' => $conference->getName(),
                    'number' => $conference->getNumber(),
                    'password' => $conference->getPassword(),
                    'mcuId' => $conference->getMcuId(),
                    'profileId' => $conference->getProfileId(),
                    'audioRecordTypeId' => $conference->getAudioRecordTypeId(),
                    'externalDS' => $conference->getExternalDS(),
                    'internalDS' => $conference->getInternalDS(),
                ];
            }
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->conference) {
                $this->destroyConference();
            }
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

    public function saveFeedBack()
    {
        if ($this->validate()) {
            $this->satisfaction = (int)$this->satisfaction;
            return $this->save(false);
        }
        return false;
    }

}