<?php
/**
 * teleport.dev
 * Created: 11.02.16 14:33
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace backend\controllers;

use backend\models\OrderSaver;
use common\models\RoomGroup;
use yii\web\Controller;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * OrderController
 */
class OrderController extends Controller
{
    public $defaultAction = 'save';

    public function actionSave()
    {
        $model = new OrderSaver(['modelClass' => RoomGroup::className()]);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', 'Порядок отображения успешно сохранен.');
            } else {
                \Yii::$app->session->setFlash('danger', 'Порядок сохранить не удалось.');
            }
        }

        return $this->render('save', ['model' => $model]);
    }
}