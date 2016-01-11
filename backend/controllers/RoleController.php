<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 10:30
 */

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\rbac\Role;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RoleController extends Controller
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
            'allModels' => Yii::$app->authManager->getRoles(),
            'sort' => [
                'attributes' => ['name', 'description', 'createdAt'],
            ]
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new RoleForm(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            return $this->redirect('index');
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $role = $this->getRole($id);
        $model = new RoleForm(['scenario' => 'update']);

        if ($model->load(Yii::$app->request->post()) && $model->update($role)) {
            return $this->redirect('index');
        }

        $model->name = $role->name;
        $model->description = $role->description;
        $model->initChildren();
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $role = $this->getRole($id);
        Yii::$app->authManager->remove($role);
        return $this->redirect('index');
    }

    /**
     * @param $name
     * @return Role
     * @throws NotFoundHttpException
     */
    private function getRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if (!$role instanceof Role) {
            throw new NotFoundHttpException("Запрашиваемая страница не найдена");
        }
        return $role;
    }
}