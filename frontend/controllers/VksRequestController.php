<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:11
 */

namespace frontend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\ModelMethodAction;
use common\models\vks\Participant;
use frontend\models\vks\ApproveRoomForm;
use frontend\models\vks\RequestForm;
use frontend\models\vks\RequestSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use common\rbac\SystemPermission;
use common\components\actions\CreateAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use frontend\models\vks\Request;
use yii\web\Response;
use yii\filters\AccessControl;

class VksRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
                    'approve' => ['post'],
                    'cancel' => ['get', 'post'],
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['approve-booking'],
                'rules' => [
                    [
                        'actions' => ['approve-booking'],
                        'allow' => true,
                        'matchCallback' => function () {
                            /** @var Participant $room */
                            $room = Participant::findOne(['_id' => \Yii::$app->request->get('roomId')]);
                            return \Yii::$app->user->identity['_id'] == $room->confirmPersonId;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => SearchAction::className(),
                'modelClass' => RequestSearch::className(),
                'scenario' => RequestSearch::SCENARIO_SEARCH_SCHEDULE,
                'pjaxView' => '_schedule'
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => RequestForm::className(),
                'permission' => SystemPermission::CREATE_REQUEST,
                'successMessage' => 'Заявка создана'
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => RequestForm::className(),
                'permission' => SystemPermission::UPDATE_REQUEST,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Request::className(),
                'permission' => SystemPermission::DELETE_REQUEST,
                'successMessage' => 'Заявка удалена'
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => Request::className(),
            ],
            'approve' => [
                'class' => ModelMethodAction::className(),
                'modelClass' => Request::className(),
                'modelMethod' => ['approve'],
                'scenario' => Request::SCENARIO_APPROVE,
                'permission' => SystemPermission::APPROVE_REQUEST,
                'successMessage' => 'Заявка согласована'
            ],
            'cancel' => [
                'class' => UpdateAction::className(),
                'modelClass' => Request::className(),
                'modelMethod' => ['cancel'],
                'scenario' => Request::SCENARIO_CANCEL,
                'permission' => SystemPermission::CANCEL_REQUEST,
                'view' => 'cancel',
                'successMessage' => 'Заявка отменена'
            ]
        ];
    }

    public function actionRefreshParticipants($requestId = null)
    {
        if (\Yii::$app->request->isAjax) {
            $model = ($requestId === null) ? new RequestForm() : RequestForm::findOne($requestId);
            $model->scenario = RequestForm::SCENARIO_REFRESH_PARTICIPANTS;

            if ($model->load(\Yii::$app->request->get()) && $model->validate()) {
                return $this->renderAjax('_participants', ['model' => $model]);
            }
        }
        throw new NotFoundHttpException('Страница не найдена');
    }

    public function actionSetDeployServer($id)
    {
        $request = \Yii::$app->request;
        /** @var Request $model */
        $model = Request::findOne($id);

        if ($request->isAjax && $model !== null) {
            $model->scenario = Request::SCENARIO_SET_DEPLOY_SERVER;
            if ($model->load($request->post()) && $model->save()) {
                return new Response();
            } else {
                throw new HttpException('Невозможно установить сервер сборки');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function actionPrint($id)
    {
        $model = Request::findOne(['_id' => new \MongoId($id), 'status' => Request::STATUS_APPROVE]);
        if ($model) {
            return $this->renderPartial('print', ['model' => $model]);
        }
        throw new NotFoundHttpException;
    }

    public function actionApproveBooking($roomId, $requestId, $status = Participant::STATUS_APPROVE)
    {
        $request = Request::findOne(['_id' => $requestId]);
        if (!$request instanceof Request) {
                throw new NotFoundHttpException("Заявка на бронирование не найдена.");
        }
        $model = new ApproveRoomForm([
            'approvedRoomId' => $roomId,
            'request' => $request
        ]);

        if ($status === Participant::STATUS_CANCEL) {
            $model->scenario = ApproveRoomForm::SCENARIO_CANCEL_ROOM;
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($status === Participant::STATUS_APPROVE) {
                $model->approveRoom($roomId);
            } elseif ($status === Participant::STATUS_CANCEL) {
                $model->cancelRoom($roomId);
            }
            return $this->redirect(['user/booking-approve-list']);
        }

        return $this->render('approve-room', ['model' => $model]);
    }
}