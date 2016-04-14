<?php
return [
    'language' => 'ru-RU',

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'components' => [
        
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
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

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
        
        'authManager' => [
            'class' => \yii\rbac\DbManager::className(),
        ],
        
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
        ]
        
    ],

    'modules' => require(__DIR__ . '/modules.php'),
];
