<?php
/**
 * oskr.local
 * Created: 16.05.16 10:27
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace common\components\helpers\mail;
use yii\base\Object;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Mail
 */

class Mail extends Object
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

    public function send()
    {
        \Yii::$app->mailer->compose(['html' => $this->view], $this->viewParams)
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->send();
    }
}