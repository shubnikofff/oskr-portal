<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components;

use common\components\events\MailerEvent;
use yii\base\Component;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Mailer
 */

class Mailer extends Component
{
    public function send(MailerEvent $event)
    {
        \Yii::$app->mailer->compose(['html' => $event->view], $event->viewParams)
            ->setFrom($event->from)
            ->setTo($event->to)
            ->setSubject($event->subject)
            ->send();
    }
}