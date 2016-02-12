<?php

namespace common\models\vks;

use common\models\Company;
use frontend\models\vks\Request;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\mongodb\validators\MongoIdValidator;
use yii\mongodb\Collection;

/**
 * This is the model class for collection "vks.participant".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $shortName
 * @property \MongoId|string $companyId
 * @property Company $company
 * @property boolean $ahuConfirmation
 * @property string $phone
 * @property string $contact
 * @property string $model
 * @property string $ipAddress
 * @property string $gatekeeperNumber
 * @property string $note
 * @property bool|null $isBusy
 * @property int|null $busyFrom
 * @property int|null $busyTo
 */
class Participant extends ActiveRecord
{
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
            'phone',
            'contact',
            'model',
            'ipAddress',
            'gatekeeperNumber',
            'note',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'shortName'], 'required'],

            ['companyId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            ['companyId', 'exist', 'targetClass' => Company::className(), 'targetAttribute' => '_id'],

            ['ahuConfirmation', 'boolean'],
            ['ahuConfirmation', 'filter', 'filter' => function ($value) {
                return boolval($value);
            }],

            ['ipAddress', 'match', 'pattern' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/'],
            [['phone', 'contact', 'model', 'gatekeeperNumber', 'note'], 'safe'],
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['_id' => 'companyId']);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'shortName' => 'Краткое название',
            'status' => 'Статус',
            'companyId' => 'Компания',
            'ahuConfirmation' => 'Необходимость согласования с АХУ',
            'phone' => 'Телефон',
            'contact' => 'Контактное лицо',
            'model' => 'Модель оборудования',
            'ipAddress' => 'IP адрес',
            'gatekeeperNumber' => 'Номер на GateKeeper',
            'note' => 'Примечание',
        ];
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
            ['$project' => [
                'date' => 1,
                'participantsId' => 1,
                'beginTime' => 1,
                'endTime' => 1,
                'status' => 1
            ]],
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
        $participants = self::find()->with('company')->orderBy('name')->all();
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
}
