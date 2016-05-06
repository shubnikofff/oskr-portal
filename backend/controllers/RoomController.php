<?php

namespace backend\controllers;

use app\models\RoomForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Room;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use app\models\RoomSearch;
/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
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
                'modelClass' => RoomSearch::className()
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => Room::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => RoomForm::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => RoomForm::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Room::className()
            ]
        ];
    }
}
