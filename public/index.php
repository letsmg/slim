<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        putenv(trim($line));
    }
}

// Carrega a configuração
$config = require __DIR__ . '/../config/config.php';

$app = AppFactory::create();

// Middleware de erro
$errorMiddleware = $app->addErrorMiddleware(
    (bool) ($config['app']['debug'] ?? false),
    true,
    true
);

// Carrega as rotas
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();