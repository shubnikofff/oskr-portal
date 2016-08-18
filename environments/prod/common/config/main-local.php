<?php
return [
    'components' => [
        'mongodb' => [
            'dsn' => 'mongodb://mongo:27017/' . getenv('MONGO_DBNAME')
        ],
        'db' => [
            'dsn' => 'mysql:host=mysql;dbname=' . getenv('MYSQL_DBNAME'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
        ],
        'mailer' => [
            'transport' => [
                'host' => getenv('MAILER_HOST'),
                'username' => getenv('MAILER_USERNAME'),
                'password' => getenv('MAILER_PASSWORD'),
                'port' => getenv('MAILER_PORT'),
            ],
        ],
    ],
];
