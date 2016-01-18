<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 14:33
 */

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\rbac\Permission;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\models\PermissionForm;
use yii\filters\VerbFilter;


class PermissionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getPermissions(),
            'sort' => [
                'attributes' => ['name', 'ruleName', 'description', 'createdAt'],
            ]
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $permission = $this->getPermission($id);
        $model = new PermissionForm();

        if ($model->load(Yii::$app->request->post()) && $model->update($permission)) {
            return $this->redirect('index');
        }
        $model->name = $permission->name;
        $model->ruleName = $permission->ruleName;
        $model->description = $permission->description;
        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param $name
     * @return Permission
     * @throws NotFoundHttpException
     */
    private function getPermission($name)
    {
        $permission = Yii::$app->authManager->getPermission($name);
        if (!$permission instanceof Permission) {
            throw new NotFoundHttpException('Привилегия не найдена');
        }
        return $permission;
    }
}