<?php
/**
 * oskr.local
 * Created: 17.05.16 8:10
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace common\components\events;
use common\components\helpers\mail\Mail;
use common\components\helpers\mail\MailProvider;
use common\models\vks\Participant;
use frontend\models\vks\Request;
use yii\base\Event;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RoomStatusChangedEvent
 */

class RoomStatusChangedEvent extends Event implements MailProvider
{
    /**
     * @var string
     */
    public $roomStatus;
    /**
     * @var Request
     */
    public $request;
    
    public function getMail()
    {
        /** @var Participant $sender */
        $sender = $this->sender;
        return new Mail([
            'to' => $sender->confirmPerson->email,
            'subject' => 'Согласование брони помещения',
            'view' => 'confirmRoom',
            'viewParams' => [
                'room' => $sender,
                'request' => $this->request
            ]
        ]);
    }

}