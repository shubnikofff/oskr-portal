<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 24.08.15
 * Time: 11:21
 */
return [
    'user' => [
        'identityClass' => 'common\models\User',
        'enableAutoLogin' => true,
    ],

    'request' => [
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ]
    ],

    'errorHandler' => [
        'errorAction' => 'site/error',
    ],

    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'rest/user',
                'tokens' => [
                    '{id}' => '<id:\w+>'
                ]
            ],
        ],
    ],
];