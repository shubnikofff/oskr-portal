<?php
/**
 * oskr.local
 * Created: 16.05.16 10:30
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace common\components\helpers\mail;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * MailProvider
 */

interface MailProvider
{
    /**
     * @return Mail
     */
    public function getMail();
}