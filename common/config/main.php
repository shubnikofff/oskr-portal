<?php
return [
    'name' => 'ОСКР Портал',

    'language' => 'ru-RU',

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'timeZone' => 'Europe/Moscow',

    'bootstrap' => ['log'],

    'components' => [

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            /*'class' => 'yii\mongodb\rbac\MongoDbManager',
            'itemCollection' => 'auth.item',
            'assignmentCollection' => 'auth.assignment',
            'ruleCollection' => 'auth.rule'*/
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8'
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],

    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['127.0.0.1', '::1'],
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                ],
                'httpclient' => [
                    'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
                ]
            ],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1']
        ]
    ],
];
