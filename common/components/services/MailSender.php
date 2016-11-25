<?php
/**
 * oskr-portal
 * Created: 24.11.16 11:18
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace common\components\services;
use yii\base\Exception;
use yii\mail\MessageInterface;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * MailSender
 */

class MailSender
{
    public static function send(MessageInterface $message)
    {
        try {
            $message->send();
        } catch (Exception $exception) {
            \Yii::$app->session->setFlash('warning', 'Некоторые сообщения не были отправлены');
        }
    }
}