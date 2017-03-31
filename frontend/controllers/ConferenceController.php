<?php
/**
 * oskr-portal
 * Created: 23.01.17 12:55
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\controllers;

use frontend\models\vks\ConferenceForm;
use frontend\models\vks\Request;
use frontend\models\vks\RequestSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ConferenceController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class ConferenceController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'destroy' => ['post']
                ]
            ]
        ];
    }

    public function actionCreate($requestId)
    {
        $conferenceForm = new ConferenceForm();
        if($conferenceForm->load(\Yii::$app->request->post())) {
            $request = $this->getRequest($requestId);
            if($request->createConference($conferenceForm)) {
                $request->save(false);
            }
        }
        //return $this->redirect(['index', 'RequestSearch[dateInput]' => ]);
    }

    public function actionDestroy()
    {

    }

    /**
     * @param $orderId
     * @return Request
     * @throws NotFoundHttpException
     */
    private function getRequest($orderId)
    {
        $request = Request::findOne($orderId);
        if (!$request) {
            throw new NotFoundHttpException("Заявка для данной конференции не найдена");
        }
        return $request;
    }

}