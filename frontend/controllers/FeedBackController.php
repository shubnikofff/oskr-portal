<?php
/**
 * oskr-portal
 * Created: 13.11.2017 15:02
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */
namespace frontend\controllers;
use frontend\models\vks\Request;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Class FeedBackController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class FeedBackController extends Controller
{
    public function actionIndex($requestId)
    {
        $model = self::finRequest($requestId);

        $model->scenario = $model::SCENARIO_FEEDBACK;
        if($model->load(\Yii::$app->request->post()) && $model->saveFeedBack()) {
            return $this->render('success');
        }
        return $this->render('index', ['model' => $model]);
    }

    static protected function finRequest($id)
    {
        $request = Request::findOne($id);
        if($request === null) {
            throw  new NotFoundHttpException("Страница не найдена");
        }
        return $request;
    }
}