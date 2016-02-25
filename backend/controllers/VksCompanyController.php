<?php

namespace backend\controllers;

use Yii;
use app\models\RoomGroupSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\RoomGroup;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;

/**
 * CompanyController implements the CRUD actions for RoomGroup model.
 */
class VksCompanyController extends Controller
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
                'modelClass' => RoomGroupSearch::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => RoomGroup::className()
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => RoomGroup::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => RoomGroup::className()
            ]
        ];
    }
}
