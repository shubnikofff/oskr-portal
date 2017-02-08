<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\vks\Participant;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use backend\models\VksParticipantSearch;
/**
 * RoomController implements the CRUD actions for Participant model.
 */
class VksParticipantController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
                'class' => SearchAction::class,
                'modelClass' => VksParticipantSearch::class
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Participant::class
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => Participant::class,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => Participant::class
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Participant::class
            ]
        ];
    }
}
