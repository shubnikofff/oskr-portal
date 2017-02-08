<?php
/**
 * oskr-portal
 * Created: 31.01.17 10:46
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace common\models\vks;

use yii\mongodb\ActiveRecord;


/**
 * Class AudioRecordType
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * @property string $_id
 * @property string $name
 */

class AudioRecordType extends ActiveRecord
{
    public static function collectionName()
    {
        return 'vks.audioRecordType';
    }

    public function attributes()
    {
       return [
           '_id',
           'name'
       ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Наименование'
        ];
    }

    public function rules()
    {
        return [
            [['_id', 'name'], 'required'],
            ['_id', 'number', 'min' => 0],
            ['_id', 'unique']
        ];
    }

}