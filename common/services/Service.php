<?php
/**
 * oskr-portal
 * Created: 30.01.17 11:20
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace common\services;

use yii\mail\MessageInterface;


/**
 * Class Service
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class Service
{
    protected static function sendEmail(MessageInterface $message)
    {
        try {
            \Yii::$app->mailer->send($message);
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('mail-error', "Сообщение не было доставлено адресату " . implode(", ", $message->getTo()) .
                " по причине: " . $exception->getMessage());
        }
    }

    protected static function sendMultipleEmail(array $messages)
    {
        try {
            \Yii::$app->mailer->sendMultiple($messages);
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('mail-error', "Некоторые сообщения не были доставлены по причине " . $exception->getMessage());
        }
    }


}