<?php
/**
 * oskr-portal
 * Created: 02.02.17 12:02
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

/**
 * Class PostRequest
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class PostRequest extends MCURequest
{
    
    public function send()
    {
        $conferenceName = $this->_meeting->conferenceName;
        $participants = [];
        foreach ($this->_meeting->participants as $participant) {
            if($this->_meeting->getRoomStatus($participant->_id) === $participant::STATUS_APPROVE) {
                $item = [];
                $item['participantName'] = $participant->shortName;
                $item['address'] = $participant->dialString;
                $item['protocol'] = $participant->protocol;
                $item['conferenceName'] = $conferenceName;
                $participants[] = $item;
            }
        }

        $date = date('Y-m-d', $this->_meeting->date->toDateTime()->getTimestamp());
        $data = [
            'conferenceName' => $conferenceName,
            'startTime' => $date . 'T' . $this->_meeting->beginTimeString . ":00",
            'endTime' => $date . 'T' . $this->_meeting->endTimeString . ":00",
            'mcuid' => $this->_meeting->mcuId,
            'numericId' => $this->_meeting->conferenceId,
            'pin' => $this->_meeting->conferencePassword,
            'recordType' => $this->_meeting->audioRecordTypeId,
            'Participants' => $participants
        ];

        return $this->_httpClient->post('', $data)->send();
    }
    
}