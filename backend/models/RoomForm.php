<?php
/**
 * teleport.dev
 * Created: 25.02.16 13:24
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\models;

use common\models\Room;
use common\models\RoomGroup;
use yii\helpers\ArrayHelper;
use yii\mongodb\Query;
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
    static public function groupItems()
    {
        $query = (new Query())->select(['_id', 'name'])->from(RoomGroup::collectionName());
        return ArrayHelper::map($query->all(), function ($item) {
            return (string)$item['_id'];
        }, 'name');
    }
}