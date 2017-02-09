<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:11
 */

namespace frontend\controllers;

use common\models\vks\Participant;
use common\rbac\SystemRole;
use frontend\models\vks\ApproveRoomForm;
use frontend\models\vks\RequestForm;
use frontend\models\vks\RequestSearch;
use frontend\models\vks\Schedule;
use frontend\services\MCUService;
use frontend\services\MeetingService;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\rbac\SystemPermission;
use common\components\actions\ViewAction;
use frontend\models\vks\Request;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use frontend\models\rso\File;

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
                'only' => ['create', 'approve', 'delete', 'approve-booking', 'update'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['approve', 'delete'],
                        'allow' => true,
                        'roles' => [SystemRole::OSKR]
                    ],
                    [
                        'actions' => ['approve-booking'],
                        'allow' => true,
                        'matchCallback' => function () {
                            /** @var Participant $room */
                            $room = Participant::findOne(['_id' => \Yii::$app->request->get('roomId')]);
                            return \Yii::$app->user->identity['_id'] == $room->confirmPersonId;
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            if (\Yii::$app->user->can(SystemPermission::APPROVE_REQUEST)) {
                                return true;
                            }

                            $request = Request::findOne(['_id' => \Yii::$app->request->get('id')]);

                            if (!\Yii::$app->user->can(SystemPermission::UPDATE_REQUEST, ['object' => $request])) {
                                return false;
                            }

                            $allowTime = $request->date->sec + ($request->beginTime - \Yii::$app->params['vks.allowRequestUpdateMinute']) * 60;
                            $now = time() + 3 * 60 * 60; //Минус разница формата TimeZone

                            if ($now > $allowTime) {
                                throw new ForbiddenHttpException("Заявку нельзя редактировать менее чем за " . \Yii::$app->params['vks.allowRequestUpdateMinute'] . " минут до начала мероприятия");
                            } else {
                                return true;
                            }
                        },
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Request::class,
            ],
        ];
    }

    public function actionIndex()
    {
        Url::remember();
        $model = new RequestSearch();
        $model->scenario = RequestSearch::SCENARIO_SEARCH_SCHEDULE;
        $request = \Yii::$app->request;
        $model->load($request->get());
        $viewParams = [
            'model' => $model,
            'dataProvider' => $model->search(),
            'participantsCountPerHour' => \Yii::$app->user->can(SystemPermission::APPROVE_REQUEST) ? Schedule::participantsCountPerHour($model->date) : [],
        ];
        if ($request->isPjax) {
            return $this->render('_participants', $viewParams);
        }

        return $this->render('index', $viewParams);
    }

    public function actionCreate()
    {
        $model = new RequestForm();

        if ($model->load(\Yii::$app->request->post())) {
            $model->rsoUploadedFiles = UploadedFile::getInstances($model, 'rsoUploadedFiles');

            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Заявка создана");
                return $this->redirect(Url::home());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        /** @var RequestForm $model */
        $model = self::findModel(RequestForm::class, $id);

        if ($model->load(\Yii::$app->request->post())) {
            $model->rsoUploadedFiles = UploadedFile::getInstances($model, 'rsoUploadedFiles');

            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Заявка сохранена");
                return $this->redirect(Url::home());
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        /** @var Request $model */
        $model = self::findModel(Request::class, $id);
        MCUService::destroyConference($model);
        if($model->delete()) {
            \Yii::$app->session->setFlash('success', "Совещание удалено.");
        }
        return $this->redirect(Url::previous());
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

    public function actionApprove($requestId)
    {
        /** @var Request $model */
        $model = self::findModel(Request::class, $requestId);
        MeetingService::approve($model);
        MCUService::createConference($model, \Yii::$app->request->post());
        return $this->redirect(Url::previous());
    }

    public function actionCancel($id)
    {
        $model = self::findModel(Request::class, $id);
        /** @var Request $model */
        if (!\Yii::$app->user->can(SystemPermission::CANCEL_REQUEST, ['object' => $model])) {
            throw new ForbiddenHttpException("Вы не можете отменить эту заявку. Операция запрещена.");
        }
        $model->scenario = Request::SCENARIO_CANCEL;
        if($model->load(\Yii::$app->request->post())) {
            MeetingService::cancel($model);
            return $this->redirect(Url::previous());
        }
        return $this->render('cancel', ['model' => $model]);
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
            if ($status === Participant::STATUS_APPROVE) {
                $model->approveRoom($roomId);
            } elseif ($status === Participant::STATUS_CANCEL) {
                $model->cancelRoom($roomId);
            }
            return $this->redirect(['user/booking-approve-list']);
        }

        return $this->render('approve-room', ['model' => $model]);
    }

    public function actionRenderFile($id)
    {
        /** @var File $model */
        $model = self::findModel(File::class, $id);
        $response = \Yii::$app->response;
        $response->headers->set('Content-type', $model->mimeType);
        $response->headers->set('Content-Disposition', 'inline; filename="'.$model->filename.'"');
        $response->statusCode = 200;
        $response->format = Response::FORMAT_RAW;
        $response->data = $model->getFileContent();
        return $response;
    }

    private static function findModel($modelClass, $id)
    {
        $model = call_user_func([$modelClass, 'findOne'], $id);

        if ($model === null) {
            throw new NotFoundHttpException("Старница не найдена.");
        }

        return $model;
    }
}