<?php
/**
 * oskr-portal
 * Created: 20.09.16 15:26
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\controllers;

use common\rbac\SystemPermission;
use common\rbac\SystemRole;
use frontend\models\rso\File;
use frontend\models\vks\Request;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RsoController
 */
class RsoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    '*' => ['get']
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [SystemRole::RSO, SystemRole::OSKR]
                    ]
                ]
            ]
        ];
    }

    public function actionListRequests()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Request::find()->where(['rsoAgreement' => ['$ne' => Request::RSO_AGREEMENT_NO_NEED]])->orderBy(['date' => -1])
        ]);
        return $this->render('list-requests', ['dataProvider' => $dataProvider]);
    }

    public function actionViewRequest($id)
    {
        $model = $this->findModel(Request::class, $id);
        return $this->render('view-request', ['model' => $model]);
    }

    public function actionRenderFile($id)
    {
        /** @var File $model */
        $model = $this->findModel(File::class, $id);
        $response = \Yii::$app->response;
        $response->headers->set('Content-type', $model->mimeType);
        $response->statusCode = 200;
        $response->format = Response::FORMAT_RAW;
        $response->data = $model->getFileContent();
        return $response;
    }

    public function actionApproveRequest($id)
    {
        /** @var Request $model */
        $model = $this->findModel(Request::class, $id);
        if ($model->rsoApprove()) {
            \Yii::$app->session->setFlash('success', 'Заявка одобрена');
        }
        return $this->redirect('/rso/list-requests');
    }

    public function actionRefuseRequest($id)
    {
        /** @var Request $model */
        $model = $this->findModel(Request::class, $id);
        if ($model->rsoRefuse()) {
            \Yii::$app->session->setFlash('success', 'Заявка отклонена');
        }
        return $this->redirect('/rso/list-requests');
    }

    private function findModel($modelClass, $id)
    {
        $model = call_user_func([$modelClass, 'findOne'], $id);
        if ($model === null) {
            throw new NotFoundHttpException("Старница не найдена.");
        }

        return $model;
    }
}