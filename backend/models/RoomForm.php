<?php
/**
 * teleport.dev
 * Created: 25.02.16 13:24
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\models;

use common\models\Room;
use common\models\RoomGroup;
use yii\mongodb\validators\MongoIdValidator;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RoomForm
 */
class RoomForm extends Room
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'groupId'], 'required'],

            ['groupId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            ['groupId', 'exist', 'targetClass' => RoomGroup::className(), 'targetAttribute' => '_id'],

            [['bookingAgreement', 'multipleBooking'], 'boolean'],
            [['bookingAgreement', 'multipleBooking'], 'filter', 'filter' => 'boolval'],

            ['ipAddress', 'ip', 'ipv6' => false],
            [['description', 'phone', 'contactPerson', 'note'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    static public function groups()
    {
        /** @var \MongoCollection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(RoomGroup::collectionName());
        return $collection->aggregate([
            [
                '$sort' => [
                    'order' => 1
                ]
            ],
            [
                '$project' => [
                    'name' => 1,
                    'description' => 1,
                ]
            ],
        ]);
    }
}