<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace frontend\assets;

use yii\web\AssetBundle;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BookingFormAsset
 */
class BookingFormAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $js = [
        'js/bookingForm.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'frontend\assets\AppAsset',
    ];
}