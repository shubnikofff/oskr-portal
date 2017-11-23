<?php
/**
 * oskr-portal
 * Created: 20.11.2017 9:18
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\controllers;

use common\models\User;
use frontend\models\Report;
use MongoDB\BSON\Regex;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;


/**
 * Class ReportController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['work_with_reports'],
                    ]
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $model = new Report();
        $counts = $dataProvider = null;

        if ($model->load(\Yii::$app->request->get()) && $model->validate()) {
            $counts = $model->getCounts();
            $dataProvider = $model->getDataProvider();
        }
        return $this->render('index', ['model' => $model, 'counts' => $counts, 'dataProvider' => $dataProvider]);
    }

    public function actionUserList($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $regexp = new Regex(Html::decode($q), "i");
            $users = User::find()->where(['lastName' => $regexp])->all();
            $out['results'] = array_map(function ($item) {
                /** @var $item User */
                return [
                    'id' => (string)$item->_id,
                    'text' => $item->getFullNameWithPost()
                ];
            }, $users);
        } elseif (!is_null($id)) {
            $user = User::findOne($id);
            $out['results'] = ['id' => (string)$user->_id, 'text' => $user->getFullNameWithPost()];
        }
        return $out;
    }
}