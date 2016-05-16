<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\helpers\mail;

use common\components\events\MailerEvent;
use yii\base\Component;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Mailer
 */

class Mailer extends Component
{
    public function send(MailProvider $provider)
    {
        $mail = $provider->getMail();
        \Yii::$app->mailer->compose(['html' => $mail->view], $mail->viewParams)
            ->setFrom($mail->from)
            ->setTo($mail->to)
            ->setSubject($mail->subject)
            ->send();
    }
}