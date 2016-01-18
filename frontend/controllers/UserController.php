<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 14:16
 */

namespace frontend\controllers;

use common\components\actions\SearchAction;
use common\rbac\SystemPermission;
use frontend\models\vks\RequestSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\user\UpdateEmailForm;
use frontend\models\user\UpdateProfileForm;
use frontend\models\user\UpdatePasswordForm;

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
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function actions()
    {
        return [
            'requests' => [
                'class' => SearchAction::className(),
                'modelClass' => RequestSearch::className(),
                'view' => 'requests',
                'scenario' => RequestSearch::SCENARIO_SEARCH_PERSONAL,
                'permission' => SystemPermission::CREATE_REQUEST
            ]
        ];
    }


    public function actionProfile()
    {
        return $this->render('profile', ['model' => \Yii::$app->user->identity]);
    }

    public function actionUpdateProfile()
    {
        $model = new UpdateProfileForm();

        if ($model->load(\Yii::$app->request->post()) && $model->updateProfile()) {
            \Yii::$app->session->setFlash('success', 'Данные профиля успешно сохранены');
            return $this->redirect(['user/profile']);
        }

        return $this->render('updateProfile', ['model' => $model]);
    }


    public function actionUpdatePassword()
    {
        $model = new UpdatePasswordForm();

        if ($model->load(\Yii::$app->request->post()) && $model->updatePassword()) {
            \Yii::$app->session->setFlash('success', "Пароль успешно изменен");
            return $this->redirect(['user/profile']);
        }
        return $this->render('updatePassword', ['model' => $model]);
    }

    public function actionUpdateEmail()
    {
        $model = new UpdateEmailForm();

        if ($model->load(\Yii::$app->request->post()) && $model->updateEmail()) {
            \Yii::$app->session->setFlash('success', "Email успешно изменен");
            return $this->redirect(['user/profile']);
        }
        return $this->render('updateEmail', ['model' => $model]);
    }
}