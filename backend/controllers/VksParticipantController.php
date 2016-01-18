<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\vks\Participant;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use app\models\VksParticipantSearch;
/**
 * RoomController implements the CRUD actions for Participant model.
 */
class VksParticipantController extends Controller
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

    public function actions()
    {
        return [
            'index' => [
                'class' => SearchAction::className(),
                'modelClass' => VksParticipantSearch::className()
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => Participant::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Participant::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Participant::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Participant::className()
            ]
        ];
    }
}
