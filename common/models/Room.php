<?php

namespace common\models;

use frontend\models\vks\Request;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Collection;

/**
 * This is the model class for collection "room".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property \MongoId|string $groupId
 * @property RoomGroup $group
 * @property boolean $bookingAgreement
 * @property string $phone
 * @property string $contactPerson
 * @property string $equipment
 * @property string $ipAddress
 * @property string $description
 * @property bool|null $isBusy
 * @property int|null $busyFrom
 * @property int|null $busyTo
 */
class Room extends ActiveRecord
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
        return 'room';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'description',
            'groupId',
            'bookingAgreement',
            'phone',
            'contactPerson',
            'ipAddress',
            'note'
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'groupId' => 'Группа',
            'bookingAgreement' => 'Согласовывать бронирование',
            'phone' => 'Телефон',
            'contactPerson' => 'Контактное лицо',
            'equipment' => 'Оборудование',
            'ipAddress' => 'IP адрес',
            'description' => 'Описание',
        ];
    }

    public function getGroup()
    {
        return $this->hasOne(RoomGroup::className(), ['_id' => 'groupId']);
    }

    /**
     * @param Request $request
     * @return Room[]
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

        /** @var Room[] $participants */
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
