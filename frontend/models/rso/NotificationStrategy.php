<?php
/**
 * oskr-portal
 * Created: 14.09.16 14:37
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\models\rso;
use frontend\models\vks\Request;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * NotificationStrategy
 */

interface NotificationStrategy
{
    public function notify(Request $request);
}