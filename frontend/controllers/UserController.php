<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 14:16
 */

namespace frontend\controllers;

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

    public function actionRequests()
    {
        $model = new RequestSearch();
        $model->scenario = RequestSearch::SCENARIO_SEARCH_PERSONAL;
        $model->load(\Yii::$app->request->get());
        $dataProvider = $model->search();
        $this->layout = 'request-list-menu';
        return $this->render('requests', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
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

    public function actionBookingApproveList()
    {
        $this->layout = 'request-list-menu';
        return $this->render('room-approve-list', ['list' => \Yii::$app->user->identity->getRoomApproveList()]);
    }

    public function actionRequestWithoutFeedbackList()
    {
        $this->layout = 'request-list-menu';
        return $this->render('request-without-feedback-list', ['dataProvider' => (new RequestSearch())->getWithoutFeedbackList()]);
    }
}