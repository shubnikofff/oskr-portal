<?php
/**
 * oskr-portal
 * Created: 30.11.16 9:31
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace console\controllers;

use frontend\components\services\FutureMeetingGreenAtomNotifier;
use frontend\models\NotifyService;
use frontend\models\vks\Request;
use yii\console\Controller;
use MongoDB\BSON\UTCDateTime;

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
            'date' => new UTCDateTime(gmmktime(0, 0, 0) * 1000),
            'beginTime' => [
                '$gte' => $beginTime,
                '$lt' => $beginTime + $interval
            ],
            'status' => Request::STATUS_APPROVED
        ])->all();

        $this->notify($set);
    }

    public function actionEveningNotification()
    {
        $beginTime = 8 * 60;
        $tomorrowBookingSet = Request::find()->where([
            'date' => new UTCDateTime(strtotime("+1 day", gmmktime(0, 0, 0)) * 1000),
            'beginTime' => [
                '$gte' => $beginTime,
                '$lt' => $beginTime + 60,
            ],
            'status' => Request::STATUS_APPROVED
        ])->all();

        $eveningBookingSet = Request::find()->where([
            'date' => new UTCDateTime(gmmktime(0, 0, 0) * 1000),
            'beginTime' => [
                '$gte' => 18 * 60
            ]
        ])->all();

        $this->notify(array_merge($tomorrowBookingSet, $eveningBookingSet));
    }

    /**
     * @param $requestSet Request[]
     */
    protected function notify($requestSet)
    {
        foreach ($requestSet as $request) {
            try {
                NotifyService::notifySupportAboutApprovedRequest($request);
            } catch (\Exception $exception) {
                echo $exception->getMessage() . "\n";
            }
        }
    }
}