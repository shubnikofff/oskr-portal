<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * IE8Asset
 */

class IE8Asset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/ie';
    
    public $js = [
        'Array.js'
    ];

    public $jsOptions = [
        'condition'=>'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}