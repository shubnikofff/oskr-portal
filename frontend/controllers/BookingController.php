<?php
/**
 * teleport
 * Created: 09.03.16 11:26
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\controllers;
use common\components\actions\CreateAction;
use common\components\actions\UpdateAction;
use frontend\models\BookingRequestForm;
use frontend\models\BookingRequestSearch;
use yii\web\Controller;
use yii\web\Response;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingController
 */

class BookingController extends Controller
{
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => BookingRequestForm::className(),
                'view' => 'form'
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => BookingRequestForm::className(),
                'view' => 'form'
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionBusyRooms()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new BookingRequestForm(['scenario' => BookingRequestForm::SCENARIO_BUSY_ROOMS]);
        if (!$model->load(\Yii::$app->request->get())) {
            throw new \HttpInvalidParamException();
        }
        return $model->getBusyRooms(true);
    }
}