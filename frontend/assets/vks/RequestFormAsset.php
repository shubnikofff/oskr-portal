<?php
/**
 * teleport
 * Created: 22.10.15 11:31
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\assets\vks;

use yii\web\AssetBundle;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RequestFormAsset
 */
class RequestFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/vks/assets';

    public $js = [
        'js/requestForm.js'
    ];

    public $css = [
        'css/requestForm.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'frontend\assets\AppAsset',
    ];

}