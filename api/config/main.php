<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'oskr-api',
    'name' => 'oskr api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    //'defaultRoute' => 'users',
    'components' => require(__DIR__.'/components.php'),
    'params' => $params,
];
