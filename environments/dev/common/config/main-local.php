<?php

return [

    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=oskr.niaepnn.ru',
            'username' => 'root',
            'password' => '',
        ],
        'mongodb' => [
            'dsn' => 'mongodb://localhost:27017/oskr-niaepnn-ru'
        ],
        'mailer' => [
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mailtrap.io',
                'username' => '512095a5b21ecc223',
                'password' => 'ac274e6e36b363',
                'port' => '2525',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ]
        ]
    ],

    'modules' => [
        'debug' => [
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
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1']
        ]
    ],

    'bootstrap' => ['debug', 'gii']
];