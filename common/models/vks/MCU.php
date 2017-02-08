<?php
/**
 * oskr-portal
 * Created: 01.02.17 11:15
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace common\models\vks;

use yii\mongodb\ActiveRecord;

/**
 * Class MCU
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * @property string $_id
 * @property string $name
 * @property string $prefix
 * @property string $internalIp
 * @property string $externalIp
 * @property string $description
 */

class MCU extends ActiveRecord
{
    public static function collectionName()
    {
        return "vks.mcu";
    }

    public function attributes()
    {
        return [
            '_id',
            'name',
            'prefix',
            'internalIp',
            'externalIp',
            'description'
        ];
    }

    public function rules()
    {
        return [
            [['_id', 'name'],'required'],
            [['_id', 'prefix'], 'number', 'min' => 0],
            ['_id', 'unique'],
            [['internalIp', 'externalIp'], 'ip', 'ipv6' => false],
            ['description', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Наименование',
            'prefix' => 'Префикс',
            'internalIp' => 'Внутренний IP адрес',
            'externalIp' => 'Внешний IP адрес',
            'description' => 'Описание'
        ];
    }

}