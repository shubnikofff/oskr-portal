<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    require(__DIR__ . '/main-local.php'),
    require(__DIR__ . '/test.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=mysql;dbname=yii2advanced_test',
            ],
            'mongodb' => [
                'class' => '\yii\mongodb\Connection',
                'dsn' => 'mongodb://mongo:27017/tests'
            ],
        ],
    ]
);