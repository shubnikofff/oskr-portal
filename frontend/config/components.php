<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 24.08.15
 * Time: 11:21
 */
return [
    'user' => [
        'identityClass' => 'common\models\User',
        'enableAutoLogin' => true,
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            /*'<controller:(profile)>/<id:\w+>' => '<controller>/view',
            '<controller:(profile)>/<id:\w+>/<action:(create|update|delete)>' => '<controller>/<action>',*/
            //'/user/requests/?status='.\common\models\Request::STATUS_CANCEL => '/user/cancel-requests'
            //'user/requests/<status:\d+>' => 'user/requests',
        ]
    ]
];