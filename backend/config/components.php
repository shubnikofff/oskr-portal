<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 31.07.15
 * Time: 13:33
 */

return [
    'user' => [
        'identityClass' => 'common\models\User',
        'enableAutoLogin' => false,
    ],
    
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            '<controller:(permission|role|user|vks-room)>s' => '<controller>/index',
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'rest/user', 
                'tokens' => [
                    '{id}' => '<id:\w+>'
                ]
            ],
        ]
    ]
];