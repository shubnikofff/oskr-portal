<?php
/**
 * oskr-portal
 * Created: 28.07.16 8:40
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components;
use common\components\helpers\mail\Mail;
use common\models\vks\Participant;
use frontend\models\vks\Request;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * MailSender
 */

abstract class MailSender
{
    protected $_request;

    /**
     * MailSender constructor.
     * @param $request Request
     */
    public function __construct($request)
    {
        $this->_request = $request;
    }

    /**
     * @return Mail[]
     */
    abstract public function send();
}