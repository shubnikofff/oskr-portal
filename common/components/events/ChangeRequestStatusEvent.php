<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace common\components\events;
use frontend\models\vks\Request;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ChangeRequestStatusEvent
 */

class ChangeRequestStatusEvent extends MailerEvent
{
    /** @var  Request */
    public $request;

    public function init()
    {
        $this->to = $this->request->owner->email;
        $this->subject = 'Изменение статуса заявки';
        $this->view = 'vksRequestStatusChanged-html';
        $this->viewParams = ['model' => $this->request];
        
        parent::init();
    }
}