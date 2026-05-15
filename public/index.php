<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env com phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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