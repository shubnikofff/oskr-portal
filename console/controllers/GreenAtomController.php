<?php
/**
 * oskr-portal
 * Created: 30.11.16 9:31
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace console\controllers;

use frontend\components\services\FutureMeetingGreenAtomNotifier;
use frontend\models\vks\Request;
use yii\console\Controller;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * GreenAtomController
 */
class GreenAtomController extends Controller
{
    public function actionPeriodicNotification($interval, $inTime = 60)
    {
        $beginTime = intval(date('G')) * 60 + intval(date('i')) + intval($inTime);
        $set = Request::find()->where([
            'date' => new \MongoDate(gmmktime(0, 0, 0)),
            'beginTime' => [
                '$gte' => $beginTime,
                '$lt' => $beginTime + $interval
            ],
            'status' => Request::STATUS_APPROVE
        ])->all();

        self::notify($set);
    }

    public function actionEveningNotification()
    {
        $beginTime = 8 * 60;
        $tomorrowBookingSet = Request::find()->where([
            'date' => new \MongoDate(strtotime("+1 day", gmmktime(0, 0, 0))),
            'beginTime' => [
                '$gte' => $beginTime,
                '$lt' => $beginTime + 60,
            ],
            'status' => Request::STATUS_APPROVE
        ])->all();

        $eveningBookingSet = Request::find()->where([
            'date' => new \MongoDate(gmmktime(0, 0, 0)),
            'beginTime' => [
                '$gte' => 18 * 60
            ]
        ])->all();

        self::notify(array_merge($tomorrowBookingSet, $eveningBookingSet));
    }

    /**
     * @param $requestSet Request[]
     */
    protected static function notify($requestSet)
    {
        foreach ($requestSet as $request) {
            FutureMeetingGreenAtomNotifier::sendMail($request);
        }
    }
}