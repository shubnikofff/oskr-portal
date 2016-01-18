<?php

namespace common\models\vks;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "vks.deployServer".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 */
class DeployServer extends ActiveRecord
{
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
        ];
    }
}
