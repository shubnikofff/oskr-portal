<?php
/**
 * oskr-portal
 * Created: 14.09.16 14:49
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\rso;

use common\models\User;
use common\rbac\SystemRole;
use frontend\models\vks\Request;
use yii\helpers\ArrayHelper;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RsoNotificationStrategy
 */
class RsoNotificationStrategy implements NotificationStrategy
{
    public function notify(Request $request)
    {
        if ($request->rsoAgreement === Request::RSO_AGREEMENT_IN_PROCESS) {
            $eventName = $request->isNewRecord ? Request::EVENT_AFTER_INSERT : Request::EVENT_AFTER_UPDATE;
            $request->on($eventName, function ($event) {
                $query = User::find()->where(['_id' => \Yii::$app->authManager->getUserIdsByRole(SystemRole::RSO)])->asArray();
                $emails = ArrayHelper::getColumn($query->all(), 'email');
                \Yii::$app->mailer->compose('rso-new-agreement', ['request' => $event->sender])
                    ->setFrom([\Yii::$app->params['email.admin'] => \Yii::$app->name])
                    ->setTo($emails)
                    ->setSubject('Необходимо согласование заявки')
                    ->send();
            });
        }
    }

}