<?php
/**
 * oskr-portal
 * Created: 02.02.17 14:09
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

/**
 * Class DeleteRequest
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class DeleteRequest extends MCURequest
{

    public function send()
    {
        return $this->_httpClient->delete('', [
            'conferenceName' => $this->_meeting->conferenceName,
            'mcuid' => $this->_meeting->mcuId,
        ])->send();
    }

}