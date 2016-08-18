<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'teleport-backend',
    'name' => 'ОСКР Портал',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'params' => $params,
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'rules' => [
                '<controller:(permission|role|user|vks-room)>s' => '<controller>/index',
            ]
        ]
    ]
];
