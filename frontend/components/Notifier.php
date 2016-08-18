<?php
/**
 * oskr-portal
 * Created: 27.07.16 15:00
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */
namespace frontend\components;

use frontend\models\vks\Request;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Notifier
 */
class Notifier
{
    /**
     * @param $event \yii\base\Event
     */
    /*    public function notify($event)
        {
            $request = $event->sender;
            $participantsId = array_merge($request->participantsId, $request->getOldAttribute('participantsId'));
            $result = array_map("unserialize", array_unique(array_map("serialize", $participantsId)));



            if($request->isNewRecord) {

            }

        }*/


    public function onRequestAfterInsert($event)
    {
        /** @var Request $request */
        $request = $event->sender;
        (new NewRequestMailSender($request))->send();
    }


    public function onRequestBeforeUpdate($event)
    {
        /** @var Request $request */
        $request = $event->sender;

        switch ($request->status) {
            case Request::STATUS_OSKR_CONSIDERATION:
                (new UpdateRequestMailSender($request))->send();
                break;
            case Request::STATUS_CANCEL:
                (new CancelRequestMailSender($request))->send();
                break;
            default:
                return;
        }
    }

    public function onRequestAfterDelete($event)
    {
        /** @var Request $request */
        $request = $event->sender;
        (new CancelRequestMailSender($request))->send();
    }
}