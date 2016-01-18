<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\models\vks\DeployServer;
use app\models\VksDeployServerSearch;

/**
 * VksDeployServerController implements the CRUD actions for DeployServer model.
 */
class VksDeployServerController extends Controller
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
                'modelClass' => VksDeployServerSearch::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => DeployServer::className()
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => DeployServer::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => DeployServer::className()
            ]
        ];
    }

}
