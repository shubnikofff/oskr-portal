<?php
/**
 * teleport
 * Created: 12.01.16 14:33
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace frontend\assets;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RespondAsset for IE8 support
 */

class RespondAsset extends AssetBundle
{
    public $sourcePath = '@bower/respond/dest';
    public $js = [
        'respond.min.js',
    ];
    public $jsOptions = [
        'condition'=>'lt IE 9',
        'position' => View::POS_HEAD,
    ];
}