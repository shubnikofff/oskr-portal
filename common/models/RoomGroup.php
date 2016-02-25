<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "room.group".
 *
 * @property \MongoId $_id
 * @property string $name
 * @property string $description
 */
class RoomGroup extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'room.group';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'description'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['description', 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'description' => 'Описание',
        ];
    }
}
