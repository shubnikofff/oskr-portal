<?php

namespace common\models;

use Yii;
use yii\mongodb\ActiveQuery;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "vks.company".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $address
 */
class Company extends ActiveRecord
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
     * @inheritDoc
     */
/*    public function fields()
    {
        return array_merge(parent::fields(),[
            'id' => function(){return (string)$this->getPrimaryKey();}
        ]);
    }*/

    /**
     *  @return ActiveQuery;
     */
/*    public function getRooms() {
        return $this->hasMany(Participant::className(),['companyId'=>'_id']);
    }*/

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
