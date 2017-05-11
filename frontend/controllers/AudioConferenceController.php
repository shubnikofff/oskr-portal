<?php
/**
 * oskr-portal
 * Created: 11.05.17 12:44
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\controllers;

use frontend\models\audioconference\AudioConferenceService;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Class AudioConferenceController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class AudioConferenceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'delete'],
                        'roles' => ['@'],
                    ],

                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'create' => ['post'],
                    'delete' => ['post', 'delete']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', ['conference' => (new AudioConferenceService())->getConferenceByUserId(\Yii::$app->user->getId())]);
    }

    public function actionCreate()
    {
        return $this->render('index', ['conference' => (new AudioConferenceService())->createConferenceForUser(\Yii::$app->user->getId())]);
    }

    public function actionDelete()
    {
        (new AudioConferenceService())->deleteConferenceForUser(\Yii::$app->user->getId());
        return $this->render('index', ['conference' => null]);
    }
}