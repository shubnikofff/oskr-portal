<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 09.10.15
 * Time: 13:47
 */
//use kartik\datecontrol\Module as DateControl;

return [
    'rest' => 'common\modules\rest\Module'
    /*'datecontrol' => [
        'class' => DateControl::className(),

        'displaySettings' => [
            DateControl::FORMAT_DATE => 'd MMMM y г.',
            DateControl::FORMAT_TIME => 'HH:mm',
            DateControl::FORMAT_DATETIME => 'd MMMM HH:mm',
        ],

        'saveSettings' => [
            DateControl::FORMAT_DATE => 'php:U',
            DateControl::FORMAT_TIME => 'php:H:i',
            DateControl::FORMAT_DATETIME => 'php:U',
        ],

        'autoWidgetSettings' => [
            DateControl::FORMAT_DATE => [
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true
                ]
            ],
            DateControl::FORMAT_TIME => [
                'pluginOptions' => [
                    'defaultTime' => false,
                    'showSeconds' => false,
                    'showMeridian' => false,
                ]
            ],
            DateControl::FORMAT_DATETIME => [
                'pluginOptions' => [
                    'autoclose' => true,
                ]
            ],
        ],
    ]*/
];