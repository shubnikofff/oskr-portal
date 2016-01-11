<?php

namespace backend\controllers;

use Yii;
use app\models\VksCompanySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Company;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;

/**
 * CompanyController implements the CRUD actions for Company model.
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
                'modelClass' => VksCompanySearch::className()
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Company::className()
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Company::className()
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Company::className()
            ]
        ];
    }
}
