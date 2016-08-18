<?php
/**
 * oskr-portal
 * Created: 28.07.16 12:18
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\components;
use frontend\models\vks\Request;
/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UpdateRequestMailSender
 */

class UpdateRequestMailSender extends MailSender
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $ids = array_merge($request->participantsId, $request->getOldAttribute('participantsId'));
        $ids = array_map("unserialize", array_unique(array_map("serialize", $ids)));
    }


    public function send()
    {

    }
}