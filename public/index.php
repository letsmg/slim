<?php

use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env com phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Carrega a configuração
$config = require __DIR__ . '/../config/config.php';

// Inicializa o Logger do sistema (Monolog com RotatingFileHandler)
// Usa APP_ENV do config para definir nível de log automático
\App\Logging\Logger::channel('app')->info('Sistema iniciado', [
    'env'  => $config['app']['env'] ?? 'production',
    'php'  => PHP_VERSION,
    'host' => $_SERVER['HTTP_HOST'] ?? 'cli',
]);

// Configura sessão segura
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if ($config['app']['env'] === 'production') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// Configura Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $config['database']['connection'],
    'host'      => $config['database']['host'],
    'port'      => $config['database']['port'],
    'database'  => $config['database']['database'],
    'username'  => $config['database']['username'],
    'password'  => $config['database']['password'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = AppFactory::create();

// Adiciona body parsing middleware (para JSON, form data)
$app->addBodyParsingMiddleware();

// Middleware de erro (debug ativado apenas em ambiente local)
$errorMiddleware = $app->addErrorMiddleware(
    (bool) ($config['app']['debug'] ?? false),
    true,
    true
);

// Carrega as rotas
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

// Carrega assets compilados pelo Vite (com cache via manifest.json)
$viteAssets = vite_assets();

// Disponibiliza para o template renderizado
$app->getContainer()?->set('viteAssets', $viteAssets);

$app->run();
