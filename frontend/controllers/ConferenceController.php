<?php
/**
 * oskr-portal
 * Created: 23.01.17 12:55
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\controllers;

use frontend\models\vks\ConferenceForm;
use frontend\models\vks\MCUProfileRepository;
use frontend\models\vks\Request;
use function GuzzleHttp\Psr7\str;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
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
        if ($conferenceForm->load(\Yii::$app->request->post())) {
            $request = $this->getRequest($requestId);
            if ($request->createConference($conferenceForm)) {
                $request->save(false);
            }
        }
        return $this->redirect(Url::previous());
    }

    public function actionDestroy($requestId)
    {
        $request = $this->getRequest($requestId);
        $request->destroyConference();
        $request->save(false);
        return $this->redirect(Url::previous());
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

    public function actionGetProfiles()
    {
        $mcuId = \Yii::$app->request->post('depdrop_parents')[0];
        echo json_encode(['output' => MCUProfileRepository::instance()->getRaw($mcuId), 'selected' => null]);
    }
}