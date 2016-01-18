<?php
/**
 * teleport
 * Created: 16.11.15 13:56
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\assets\vks;
use yii\web\AssetBundle;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * SearchFormAsset
 */

class SearchFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/vks/assets';

    public $js = [
        'js/schedule.js'
    ];

    public $css = [
        'css/schedule.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'frontend\assets\AppAsset',
    ];
}