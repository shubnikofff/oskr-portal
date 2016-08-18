<?php
/**
 * oskr-portal
 * Created: 28.07.16 10:49
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components;
use common\components\helpers\mail\Mail;
use common\models\vks\Participant;
use frontend\models\vks\Request;
/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * NewRequestMailSender
 */

class NewRequestMailSender extends MailSender
{
    /**
     * @var Participant[]
     */
    private $_notifyParticipants;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->_notifyParticipants = Participant::find()
            ->with('company')
            ->where([
            '_id' => ['$in' => $this->_request->participantsId],
            'supportEmails' => ['$exists' => 1]
        ])->all();
    }

    public function send()
    {
        foreach ($this->_notifyParticipants as $participant) {
            (new Mail([
                'to' => $participant->supportEmails,
                'subject' => 'Новое бронирование помещения',
                'view' => 'newBooking',
                'viewParams' => [
                    'request' => $this->_request,
                    'participant' => $participant
                ]
            ]))->send();
        }
    }
}