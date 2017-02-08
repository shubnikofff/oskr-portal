<?php
/**
 * oskr-portal
 * Created: 02.02.17 14:17
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\services;

/**
 * Class GetRequest
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class GetRequest extends MCURequest
{

    public function send()
    {
        return $this->_httpClient->get($this->_meeting->conferenceName)->send();
    }

}