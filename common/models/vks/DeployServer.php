<?php

namespace common\models\vks;

use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "vks.deployServer".
 *
 * @property \MongoId|string $_id
 * @property string $name
 * @property string $ip
 * @property string $brand
 */
class DeployServer extends ActiveRecord
{
    const BRAND_CISCO = 'cisco';
    const BRAND_POLYCOM = 'polycom';

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'vks.deployServer';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'ip',
            'brand'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ip', 'brand'], 'required'],
            ['ip', 'ip', 'ipv6' => false],
            ['brand', 'in', 'range' => [self::BRAND_CISCO, self::BRAND_POLYCOM]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'ip' => 'IP адрес',
            'brand' => 'Брэнд'
        ];
    }
}
