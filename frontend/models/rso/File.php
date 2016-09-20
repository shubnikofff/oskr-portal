<?php
/**
 * oskr-portal
 * Created: 19.09.16 15:36
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\rso;
use yii\mongodb\file\ActiveRecord;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * File
 *
 * @property $filename string
 * @property $mimeType string
 */

class File extends ActiveRecord
{
    public static function collectionName()
    {
        return 'rso';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'mimeType'
        ]);
    }

}