<?php
/**
 * teleport
 * Created: 12.01.16 14:29
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\assets;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Html5ShivAsset HTML5 support for IE8
 */

class Html5ShivAsset extends AssetBundle
{
    public $sourcePath = '@bower/html5shiv/dist';
    public $js = [
        'html5shiv.min.js',
    ];
    public $jsOptions = [
        'condition'=>'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}