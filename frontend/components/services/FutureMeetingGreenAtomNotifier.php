<?php
/**
 * oskr-portal
 * Created: 25.11.16 9:36
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components\services;

use common\models\vks\Participant;
use frontend\models\vks\Request;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * FutureMeetingGreenAtomNotifier
 */
class FutureMeetingGreenAtomNotifier extends GreenAtomNotifier
{
    static protected function doComposeMessage(Request $request, Participant $participant)
    {
        return \Yii::$app->mailer->compose(['text' => 'futureMeetingGreenAtomNotification-text'], ['request' => $request, 'participant' => $participant])
            ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
            ->setTo($participant->supportEmails)
            ->setSubject('Уведомление о предстоящем совещании');
    }

}