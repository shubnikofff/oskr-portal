<?php
/**
 * teleport
 * Created: 23.12.15 8:09
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\assets\vks;
use yii\web\AssetBundle;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * DeployServerFormAsset
 */

class DeployServerFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/vks/assets';

    public $js = [
        'js/deployServerForm.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'frontend\assets\AppAsset',
    ];
}