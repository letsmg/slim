<?php

return [
    'app' => [
        'name'  => $_ENV['APP_NAME'] ?? 'SlimApp',
        'env'   => $_ENV['APP_ENV'] ?? 'production',
        'debug' => (bool) ($_ENV['APP_DEBUG'] ?? false),
        'url'   => $_ENV['APP_URL'] ?? 'http://localhost',
    ],

    'database' => [
        'connection' => $_ENV['DB_CONNECTION'] ?? 'mysql',
        'host'       => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port'       => $_ENV['DB_PORT'] ?? '3306',
        'database'   => $_ENV['DB_DATABASE'] ?? 'slimapp',
        'username'   => $_ENV['DB_USERNAME'] ?? 'root',
        'password'   => $_ENV['DB_PASSWORD'] ?? '',
    ],

    'session' => [
        'driver'   => $_ENV['SESSION_DRIVER'] ?? 'file',
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120,
    ],

    'cache' => [
        'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
    ],

    'mail' => [
        'mailer'       => $_ENV['MAIL_MAILER'] ?? 'smtp',
        'host'         => $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io',
        'port'         => $_ENV['MAIL_PORT'] ?? 2525,
        'username'     => $_ENV['MAIL_USERNAME'] ?? '',
        'password'     => $_ENV['MAIL_PASSWORD'] ?? '',
        'encryption'   => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@example.com',
        'from_name'    => $_ENV['MAIL_FROM_NAME'] ?? $_ENV['APP_NAME'] ?? 'SlimApp',
    ],
];