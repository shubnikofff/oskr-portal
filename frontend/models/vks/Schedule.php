<?php
/**
 * oskr-portal
 * Created: 18.10.16 13:39
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\helpers\ArrayHelper;
use yii\mongodb\Collection;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Schedule
 */
class Schedule
{
    /**
     * @param \MongoDate $date
     * @return array|string
     */
    public static function participantsCountPerHour(\MongoDate $date)
    {
        /** @var $collection Collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());

        $map = new \MongoCode("function() {
            for (var i = 8 * 60; i < 19 * 60; i = i + 30) {
                if (this.beginTime < i + 30 && this.endTime > i) {
                    emit(i, this.participantsId.length);
                }
            }
        }");

        $reduce = new \MongoCode("function (key, values) {return Array.sum(values)}");

        $out = ['inline' => true];

        $condition = [
            'date' => $date,
            'mode' => Request::MODE_WITH_VKS,
            'status' => ['$ne' => Request::STATUS_CANCEL]
        ];

        $result = $collection->mapReduce($map, $reduce, $out, $condition);

        return ArrayHelper::map($result, '_id', 'value');

    }
}