<?php
/**
 * oskr-portal
 * Created: 25.11.16 9:38
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components\services;

use common\models\vks\Participant;
use frontend\models\vks\Request;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * CancelMeetingGreenAtomNotifier
 */
class CancelMeetingGreenAtomNotifier extends GreenAtomNotifier
{
    static protected function doComposeMessage(Request $request, Participant $participant)
    {
        return \Yii::$app->mailer->compose(['text' => 'cancelMeetingGreenAtomNotification-text'], ['request' => $request, 'participant' => $participant])
            ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
            ->setTo($participant->supportEmails)
            ->setSubject('Уведомление об отмене совещании');
    }

}