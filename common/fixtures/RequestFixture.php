<?php
/**
 * oskr-portal
 * Created: 19.01.17 14:23
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace common\fixtures;

use yii\mongodb\ActiveFixture;
use frontend\models\vks\Request;

/**
 * Class Request
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class RequestFixture extends ActiveFixture
{
    public $modelClass = Request::class;
}