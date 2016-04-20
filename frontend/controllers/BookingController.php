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
use yii\web\Controller;


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
}