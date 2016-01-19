<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'teleport-frontend',
    'name' => 'Телепорт',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'vks-request/index',
    'controllerMap' => [
        //'profile' => 'frontend\controllers\UserController'
    ],
    'components' => require(__DIR__.'/components.php'),
    'params' => $params,
];