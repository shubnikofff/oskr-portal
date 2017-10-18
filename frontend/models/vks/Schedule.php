<?php
/**
 * oskr-portal
 * Created: 18.10.16 13:39
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\helpers\ArrayHelper;
use yii\mongodb\Collection;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\Javascript;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Schedule
 */
class Schedule
{
    /**
     * @var UTCDateTime
     */
    private $_date;

    /**
     * Schedule constructor.
     * @param UTCDateTime $date
     */
    public function __construct(UTCDateTime $date)
    {
        $this->_date = $date;
    }

    /**
     * @return array|string
     */
    public function participantsCountPerHour()
    {
        /** @var $collection Collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());

        $map = new Javascript("function() {
            for (var i = 7 * 60; i < 22 * 60; i = i + 30) {
                if (this.beginTime < i + 30 && this.endTime > i) {
                    emit(i, this.participantsId.length);
                }
            }
        }");

        $reduce = new Javascript("function (key, values) {return Array.sum(values)}");

        $out = ['inline' => true];

        $condition = [
            'date' => $this->_date,
            'mode' => Request::MODE_WITH_VKS,
            'status' => ['$ne' => Request::STATUS_CANCELED]
        ];

        $result = $collection->mapReduce($map, $reduce, $out, $condition);

        return ArrayHelper::map($result, '_id', 'value');

    }

    public function participantsCountOnPeriod($beginTime, $endTime)
    {
        /** @var $collection Collection */
        $collection = \Yii::$app->get('mongodb')->getCollection(Request::collectionName());

        $count = $collection->aggregate([
            ['$match' => [
                'date' => $this->_date,
                'beginTime' => ['$lt' => $endTime],
                'endTime' => ['$gt' => $beginTime],
            ]],
            ['$project' => ['_id' => 0, 'participantsId' => 1]],
            ['$unwind' => '$participantsId']
        ]);

        return count($count);
    }
}