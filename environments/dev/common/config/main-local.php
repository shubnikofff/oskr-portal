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

    'bootstrap' => ['debug', 'gii']
];