<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace common\components\events;
use common\components\helpers\mail\Mail;
use common\components\helpers\mail\MailProvider;
use frontend\models\vks\Request;
use yii\base\Event;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ChangeRequestStatusEvent
 */

class ChangeRequestStatusEvent extends Event implements MailProvider
{
    /** @var  Request */
    public $request;

    public function getMail()
    {
        return new Mail([
            'to' => $this->request->owner->email,
            'subject' => 'Изменение статуса заявки',
            'view' => 'vksRequestStatusChanged-html',
            'viewParams' => ['model' => $this->request]
        ]);
    }
}