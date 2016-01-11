<?php
/**
 * teleport
 * Created: 20.10.15 13:40
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\components\helpers;

use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ViewHelper
 */

class ViewHelper
{
    static function items($modelClass, $keyAttribute, $valueAttribute)
    {
        /** @var ActiveRecord $model */
        $model = new $modelClass();
        if (!$model instanceof ActiveRecord) {
            throw new \InvalidArgumentException("{$modelClass} must extend 'yii\\mongodb\\ActiveRecord'");
        }
        if(!$model->hasAttribute($keyAttribute)){
            throw new \InvalidArgumentException("{$keyAttribute} not found in {$modelClass}");
        }
        if(!$model->hasAttribute($valueAttribute)){
            throw new \InvalidArgumentException("{$valueAttribute} not found in {$modelClass}");
        }

        $query = $model::find()->select([$keyAttribute, $valueAttribute]);
        $models = ArrayHelper::toArray($query->all(), [
            $modelClass => [
                'key' => function($model) use ($keyAttribute){
                    return (string)$model->$keyAttribute;
                },
                'value' => function($model) use ($valueAttribute) {
                    return (string)$model->$valueAttribute;
                }
            ]
        ]);

        return ArrayHelper::map($models, 'key', 'value');
    }
}