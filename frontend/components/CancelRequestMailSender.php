<?php
/**
 * oskr-portal
 * Created: 28.07.16 9:14
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components;
use common\components\helpers\mail\Mail;
use frontend\models\vks\Request;
use common\models\vks\Participant;
/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * CancelRequestMailSender
 */

class CancelRequestMailSender extends MailSender
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
                'subject' => 'Отмена бронирования помещения',
                'view' => 'cancelBooking',
                'viewParams' => [
                    'request' => $this->_request,
                    'participant' => $participant
                ]
            ]))->send();
        }
    }

}