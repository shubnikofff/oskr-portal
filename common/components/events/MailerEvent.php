<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace common\components\events;
use yii\base\Event;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * MailerEvent
 */

class MailerEvent extends Event
{
    public $from;

    public $to;

    public $subject;

    public $view;
    
    public $viewParams;

    public function __construct($config = [])
    {
        $this->from = [\Yii::$app->params['email.admin'] => 'Служба технической поддержки ' . \Yii::$app->name];
        
        parent::__construct($config);
    }
    
}