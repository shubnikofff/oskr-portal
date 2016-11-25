<?php
/**
 * oskr-portal
 * Created: 23.11.16 15:24
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components;
use common\components\services\MailSender;
use common\models\vks\Participant;
use frontend\models\vks\Request;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * GreenAtomNotifier
 */

abstract class GreenAtomNotifier
{
    public static function sendMail(Request $request)
    {
        foreach ($request->participants as $participant) {
            if (!empty($participant->supportEmails)) {
               MailSender::send(self::doComposeMessage($request, $participant));
            }
        }
    }

    static protected function doComposeMessage(Request $request, Participant $participant) {}
}