<?php
/**
 * oskr-portal
 * Created: 26.01.17 17:19
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

use common\services\Service;
use frontend\models\vks\Request as Meeting;

/**
 * Class MeetingService
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */
class MeetingService extends Service
{
    public static function approve(Meeting &$meeting)
    {
        if ($meeting->status === Meeting::STATUS_APPROVE) {
            return;
        }
        $meeting->status = Meeting::STATUS_APPROVE;
        if ($meeting->save(false)) {
            $message = \Yii::$app->mailer->compose('meeting-approved', ['meeting' => $meeting])
                ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
                ->setTo($meeting->owner->email)
                ->setSubject("Заявка №" . $meeting->number . " согласована.");
            self::sendEmail($message);
            \Yii::$app->session->setFlash('success', "Совещение согласовано.");
        }
    }

    public static function cancel(Meeting &$meeting)
    {
        if ($meeting->status === Meeting::STATUS_CANCEL) {
            return;
        }
        $meeting->status = Meeting::STATUS_CANCEL;
        if ($meeting->save()) {
            MCUService::destroyConference($meeting);
            $message = \Yii::$app->mailer->compose('meeting-canceled', ['meeting' => $meeting])
                ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name])
                ->setTo($meeting->owner->email)
                ->setSubject("Заявка №" . $meeting->number . " отменена.");
            self::sendEmail($message);
            \Yii::$app->session->setFlash('success', "Совещение отменено.");
        }
    }

}