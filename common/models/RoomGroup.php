<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "room.group".
 *
 * @property \MongoId $_id
 * @property string $name
 * @property string $abbreviation
 * @property string $description
 * @property int $order
 */
class RoomGroup extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'roomGroup';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'abbreviation',
            'description',
            'order'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['description', 'abbreviation'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'abbreviation' => 'Аббревиатура',
            'description' => 'Описание',
        ];
    }
}
