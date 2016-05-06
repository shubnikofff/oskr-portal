<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\assets;
use yii\web\AssetBundle;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UtilAsset
 */

class UtilAsset extends AssetBundle
{
    public $sourcePath = '@common/assets';

    public $js = [
        'js/OSKR.util.js'
    ];

    public $depends = [
        OskrAsset::class
    ];
}