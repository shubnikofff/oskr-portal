<?php

return [

    'components' => [
        'db' => [
            'dsn' => 'mysql:host=mysql;dbname=' . getenv('MYSQL_DBNAME'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
        ],
        'mongodb' => [
            'dsn' => 'mongodb://mongo:27017/' . getenv('MONGO_DBNAME')
        ],
        'mailer' => [
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('MAILER_HOST'),
                'username' => getenv('MAILER_USERNAME'),
                'password' => getenv('MAILER_PASSWORD'),
                'port' => getenv('MAILER_PORT'),
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ]
    ]
];
