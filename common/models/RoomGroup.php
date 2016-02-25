<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "vks.company".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $address
 */
class RoomGroup extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'company';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'address'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['address', 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'address' => 'Адрес',
        ];
    }
}
