<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

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
    'urlManager' => [
        'enablePrettyUrl' => true,
        'enableStrictParsing' => true,
        'showScriptName' => false,
        'rules' => [
            ['class' => 'yii\rest\UrlRule', 'controller' => 'user', 'tokens' => ['{id}' => '<id:\w+>']],
        ],
    ],
    'request' => [
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ]
    ],
];