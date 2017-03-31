<?php
/**
 * oskr-portal
 * Created: 29.03.17 15:43
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models;

use frontend\models\vks\Request;


/**
 * Class NotifyService
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class NotifyService
{

    public static function notifySupportAboutApprovedRequest(Request $request)
    {
        foreach ($request->participants as $participant) {
            if ($participant->supportEmails) {
                \Yii::$app->mailer->compose(['text' => 'approvedRequestSupportNotification-text'], ['request' => $request, 'participant' => $participant])
                    ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
                    ->setTo($participant->supportEmails)
                    ->setSubject('Уведомление о согласованном совещании')
                    ->send();
            }
        }
    }

    public static function notifySupportAboutCanceledRequest(Request $request)
    {
        foreach ($request->participants as $participant) {
            if ($participant->supportEmails) {
                \Yii::$app->mailer->compose(['text' => 'canceledRequestSupportNotification-text'], ['request' => $request, 'participant' => $participant])
                    ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
                    ->setTo($participant->supportEmails)
                    ->setSubject('Уведомление об отмене совещания')
                    ->send();
            }
        }
    }

    public static function notifyOwnerAboutApprovedRequest(Request $request)
    {
        \Yii::$app->mailer->compose(['html' => 'approvedRequestOwnerNotification-html'], ['request' => $request])
            ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
            ->setTo($request->owner->email)
            ->setSubject('Заявка ' . $request->number. ' согласована')
            ->send();
    }

    public static function notifyOwnerAboutCanceledRequest(Request $request)
    {
        \Yii::$app->mailer->compose(['html' => 'canceledRequestOwnerNotification-html'], ['request' => $request])
            ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
            ->setTo($request->owner->email)
            ->setSubject('Заявка ' . $request->number. ' отменена')
            ->send();
    }

}