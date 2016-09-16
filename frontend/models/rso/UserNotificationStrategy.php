<?php
/**
 * oskr-portal
 * Created: 14.09.16 14:48
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\rso;
use frontend\models\vks\Request;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UserNotificationStrategy
 */

class UserNotificationStrategy implements NotificationStrategy
{
    public function notify(Request $request)
    {
        $request->on(Request::EVENT_AFTER_UPDATE, function($event) {
            /** @var $request Request */
            $request = $event->sender;
            \Yii::$app->mailer->compose('rso-agreement-updated',['request' => $request])
                ->setFrom([\Yii::$app->params['email.admin'] => \Yii::$app->name])
                ->setTo($request->owner->email)
                ->setSubject('Изменение статуса согласования заявки с РСО')
                ->send();
        });
    }

}