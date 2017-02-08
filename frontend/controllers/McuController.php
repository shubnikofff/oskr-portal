<?php
/**
 * oskr-portal
 * Created: 23.01.17 12:55
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\controllers;

use frontend\services\MCUService;
use frontend\models\vks\Request as Order;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class McuController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class McuController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'deploy' => ['post'],
                    'destroy' => ['post']
                ]
            ]
        ];
    }

    public function actionDeploy($requestId)
    {
        MCUService::createConference(self::getOrder($requestId), \Yii::$app->request->post());
        return $this->redirect(Url::previous());
    }

    public function actionDestroy($requestId)
    {
        MCUService::destroyConference(self::getOrder($requestId));
        return $this->redirect(Url::previous());
    }

    /**
     * @param $orderId
     * @return Order
     */
    private static function getOrder($orderId)
    {
        $request = Order::findOne($orderId);
        if (is_null($request)) {
            throw new \InvalidArgumentException("Заявка не найдена");
        }
        return $request;
    }

}