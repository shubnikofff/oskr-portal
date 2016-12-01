<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'teleport-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'vks-request/index',
    'params' => $params,
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ]
    ]
];
