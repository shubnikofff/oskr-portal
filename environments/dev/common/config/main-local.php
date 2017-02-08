<?php
$config = [];

if (!YII_ENV_TEST) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
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
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1']
    ];
}

return $config;