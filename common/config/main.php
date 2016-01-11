<?php
return [
    'language' => 'ru-RU',

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'components' => [

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
    ],

    'modules' => require(__DIR__.'/modules.php'),
];
