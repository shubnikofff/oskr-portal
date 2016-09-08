<?php

namespace common\models\vks;

use common\components\events\RequestStatusChangedEvent;
use common\components\events\RoomStatusChangedEvent;
use common\components\helpers\mail\Mailer;
use common\models\Company;
use common\models\User;
use frontend\models\vks\Request;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\mongodb\validators\MongoIdValidator;
use yii\mongodb\Collection;
use yii\validators\EachValidator;

/**
 * This is the model class for collection "vks.participant".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $shortName
 * @property \MongoId|string $companyId
 * @property Company $company
 * @property boolean $ahuConfirmation
 * @property \MongoId $confirmPersonId
 * @property array $supportEmails
 * @property User $confirmPerson
 * @property string $phone
 * @property string $contact
 * @property string $model
 * @property string $ipAddress
 * @property string $gatekeeperNumber
 * @property string $note
 * @property bool|null $isBusy
 * @property int|null $busyFrom
 * @property int|null $busyTo
 * @property array $log
 */
class Participant extends ActiveRecord
{
    const EVENT_STATUS_CHANGED = 'room_status_changed';

    const STATUS_APPROVE = 'approve';
    const STATUS_CANCEL = 'cancel';
    const STATUS_CONSIDIRATION = 'considiration';

    /**
     * @var bool is busy this participant in minute range
     */
    private $_busy;
    /**
     * @var int start minute of busy range
     */
    private $_busyFrom;
    /**
     * @var int end minute of busy range
     */
    private $_busyTo;
    /**
     * @var string
     */
    public $supportEmailsInput;

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'vks.participant';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'shortName',
            'companyId',
            'ahuConfirmation',
            'confirmPersonId',
            'supportEmails',
            'phone',
            'contact',
            'model',
            'ipAddress',
            'gatekeeperNumber',
            'note',
            'log'
        ];
    }

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_STATUS_CHANGED, function (RoomStatusChangedEvent $event) {

            if ($event->roomStatus === self::STATUS_CONSIDIRATION) {
                (new Mailer())->send($event);

            } else {
                $request = $event->request;
                $count = Participant::find()->where(['log' => ['$elemMatch' => ['requestId' => $request->_id, 'status' => self::STATUS_CONSIDIRATION]]])->count();

                if ($count === 0) {
                    $request->status = Request::STATUS_OSKR_CONSIDERATION;
                    $request->save(false);
                    $request->trigger(Request::EVENT_STATUS_CHANGED, new RequestStatusChangedEvent(['request' => $request]));
                }
            }
        });
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'shortName'], 'required'],

            ['companyId', 'exist', 'targetClass' => Company::className(), 'targetAttribute' => '_id'],

            ['ahuConfirmation', 'boolean'],
            ['ahuConfirmation', 'filter', 'filter' => function ($value) {
                return boolval($value);
            }],

            ['confirmPersonId', 'exist', 'targetClass' => User::className(), 'targetAttribute' => '_id'],

            ['supportEmailsInput', function ($attribute) {
                if ($this->{$attribute} === "") {
                    $this->supportEmails = [];
                    return;
                }
                $value = explode(';', trim($this->{$attribute}, ';'));
                $validator = new EachValidator(['rule' => ['email']]);
                if ($validator->validate($value)) {
                    $this->supportEmails = $value;
                } else {
                    $this->addError($attribute, "Неверный формат");
                }
            }, 'skipOnEmpty' => false],

            ['ipAddress', 'match', 'pattern' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/'],

            [['phone', 'contact', 'model', 'gatekeeperNumber', 'note'], 'safe'],

            [['companyId', 'confirmPersonId'], MongoIdValidator::className(), 'forceFormat' => 'object'],
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::class, ['_id' => 'companyId']);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'shortName' => 'Краткое название',
            'status' => 'Статус',
            'companyId' => 'Компания',
            'ahuConfirmation' => 'Необходимость согласования с АХУ',
            'confirmPersonId' => 'Согласующее лицо',
            'supportEmailsInput' => 'Email(ы) техподдержки',
            'phone' => 'Телефон',
            'contact' => 'Контактное лицо',
            'model' => 'Модель оборудования',
            'ipAddress' => 'IP адрес',
            'gatekeeperNumber' => 'Номер на GateKeeper',
            'note' => 'Примечание',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        if (is_array($this->supportEmails)) {
            $this->supportEmailsInput = implode(';', $this->supportEmails);
        }
    }


    public function getConfirmPerson()
    {
        return $this->hasOne(User::class, ['_id' => 'confirmPersonId']);
    }

    /**
     * @param Request $request
     * @return Participant[]
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\mongodb\Exception
     */
    static public function findAllByRequest(Request $request)
    {
        /** @var $collection Collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());

        $busyParticipants = $collection->aggregate([
            ['$match' => [
                '_id' => ['$ne' => $request->primaryKey],
                'date' => $request->date,
                'beginTime' => ['$lt' => $request->endTime],
                'endTime' => ['$gt' => $request->beginTime],
                'status' => ['$ne' => $request::STATUS_CANCEL]
            ]],
            ['$unwind' => '$participantsId'],
            ['$project' => ['_id' => 0, 'id' => '$participantsId', 'beginTime' => 1, 'endTime' => 1]]
        ]);

        /** @var Participant[] $participants */
        $participants = self::find()->with('company', 'confirmPerson')->orderBy('name')->all();
        $busyParticipantsId = ArrayHelper::getColumn($busyParticipants, 'id');

        foreach ($participants as $key => $participant) {
            $busyParticipantKey = array_search($participant->primaryKey, $busyParticipantsId);
            if ($busyParticipantKey !== false) {
                $participants[$key]->setBusy();
                $participants[$key]->busyFrom = $busyParticipants[$busyParticipantKey]['beginTime'];
                $participants[$key]->busyTo = $busyParticipants[$busyParticipantKey]['endTime'];
            }
        }
        return $participants;
    }

    public function setBusy()
    {
        $this->_busy = true;
    }

    /**
     * @return bool
     */
    public function getIsBusy()
    {
        return $this->_busy === true ? true : false;
    }

    /**
     * @return int|null
     */
    public function getBusyFrom()
    {
        return $this->_busyFrom;
    }

    /**
     * @param int $busyFrom
     */
    public function setBusyFrom($busyFrom)
    {
        $this->_busyFrom = $busyFrom;
    }

    /**
     * @return int|null
     */
    public function getBusyTo()
    {
        return $this->_busyTo;
    }

    /**
     * @param int $busyTo
     */
    public function setBusyTo($busyTo)
    {
        $this->_busyTo = $busyTo;
    }

    /**
     * @return array
     */
    static public function confirmPersonList()
    {
        $query = User::find()->asArray()->orderBy('lastName');
        return ArrayHelper::map($query->all(), function ($elem) {
            return (string)$elem['_id'];
        }, function ($elem) {
            return $elem['lastName'] . ' ' . $elem['firstName'] . ' ' . $elem['middleName'] . ' - ' . $elem['post'];
        });
    }

    /**
     * @param Request $request
     * @param $status
     * @param bool $newRecord
     */
    public function writeLog(Request $request, $status, $newRecord = false)
    {
        /** @var Collection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(self::collectionName());
        $requestId = $request->_id;

        if ($newRecord) {

            $collection->update(['_id' => $this->_id], [
                '$addToSet' => [
                    'log' => [
                        'requestId' => $requestId,
                        'status' => $status
                    ]
                ]
            ]);
        } else {
            $collection->update([
                '_id' => $this->_id,
                'log.requestId' => $requestId
            ], ['log.$.status' => $status]);
        }
    }
}
