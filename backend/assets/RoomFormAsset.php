<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace backend\assets;

use yii\web\AssetBundle;
/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * RoomFormAsset
 */

class RoomFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'js/roomForm.js',
    ];
}