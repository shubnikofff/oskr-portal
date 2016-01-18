<?php
/**
 * teleport
 * Created: 20.11.15 15:11
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\rbac;

use yii\rbac\Rule;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * OwnerRule
 */
class OwnerRule extends Rule
{
    public $name = 'isOwner';

    public function execute($user, $item, $params)
    {
        return isset($params['object']) ? (string)$params['object']->createdBy == $user : false;
    }
}