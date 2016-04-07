<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Collection;

/**
 * This is the model class for collection "room".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $description
 * @property \MongoId|string $groupId
 * @property RoomGroup $group
 * @property bool $bookingAgreement
 * @property array $agreementPerson
 * @property bool $multipleBooking
 * @property string $phone
 * @property string $contactPerson
 * @property string $ipAddress
 * @property string $note
 * @property bool|null $isBusy
 * @property int|null $busyFrom
 * @property int|null $busyTo
 */
class Room extends ActiveRecord
{

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
            'agreementPerson',
            'multipleBooking',
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
            'description' => 'Описание',
            'groupId' => 'Группа',
            'bookingAgreement' => 'Бронирование должно быть согласовано',
            'multipleBooking' => 'Возможно бронирование в нескольких мероприятиях одновременно',
            'phone' => 'Телефон',
            'contactPerson' => 'Контактное лицо',
            'ipAddress' => 'IP адрес',
            'note' => 'Примечание'
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
        $participants = self::find()->with('group')->orderBy('name')->all();
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

}
