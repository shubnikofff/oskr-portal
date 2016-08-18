<?php
$config = [
    'components' => [
        'mongodb' => [
            'dsn' => 'mongodb://mongo:27017/' . getenv('MONGO_DBNAME')
        ],
        'db' => [
            'dsn' => 'mysql:host=mysql;dbname=' . getenv('MYSQL_DBNAME'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
        ],
        'mailer' => [
            'transport' => [
                'host' => getenv('MAILER_HOST'),
                'username' => getenv('MAILER_USERNAME'),
                'password' => getenv('MAILER_PASSWORD'),
                'port' => getenv('MAILER_PORT'),
            ],
        ],
    ],
];

if (!YII_ENV_TEST) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.99.*'],
        'panels' => [
            'mongodb' => [
                'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.99.*']
    ];
}

return $config;