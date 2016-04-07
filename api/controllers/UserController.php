<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace app\controllers;

use yii\rest\ActiveController;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UserController
 */

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
}