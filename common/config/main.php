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
            'dsn' => 'mysql:host=mysql;dbname=' . getenv('MYSQL_DBNAME'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
            'class' => 'yii\db\Connection',
            'charset' => 'utf8'
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://mongo:27017/' . getenv('MONGO_DBNAME')
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('MAILER_HOST'),
                'username' => getenv('MAILER_USERNAME'),
                'password' => getenv('MAILER_PASSWORD'),
                'port' => getenv('MAILER_PORT'),
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];
