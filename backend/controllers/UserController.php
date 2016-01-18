<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 31.08.15
 * Time: 16:19
 */

namespace backend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use Yii;
use common\models\User;
use common\models\UserForm;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * @inheritDoc
     */
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
                    'change-status' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => SearchAction::className(),
                'modelClass' => UserSearch::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => User::className()
            ]
        ];
    }

    public function actionUpdate($id)
    {
        $user = $this->findUser($id);
        $model = new UserForm(['scenario' => 'update']);
        $model->username = $user->username;

        if ($model->load(Yii::$app->request->post()) && $model->update($user)) {
            $model->assignRoles($user->getId());
            return $this->redirect('index');
        }

        $model->email = $user->email;
        $model->lastName = $user->lastName;
        $model->firstName = $user->firstName;
        $model->middleName = $user->middleName;
        $model->division = $user->division;
        $model->post = $user->post;
        $model->phone = $user->phone;
        $model->mobile = $user->mobile;
        $model->initRoles($user->getId());

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    public function actionChangeStatus($id, $status){
        $user = $this->findUser($id);
        $user->status = $status;
        if($user->save()){
            return $this->redirect('index');
        }
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUser($id) {
        $user = User::findOne($id);
        if(is_null($user)){
            throw new NotFoundHttpException("Пользователь не найден");
        }
        return $user;
    }

}