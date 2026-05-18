<?php

use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Controllers\VehicleController;
use App\Controllers\DriverController;
use App\Controllers\HomeController;
use App\Controllers\MechanicController;
use App\Controllers\TripController;
use App\Controllers\ScheduledMaintenanceController;
use App\Controllers\ReportController;
use App\Controllers\UserController;
use App\Services\ReportService;
use App\Services\VehicleService;
use App\Services\DriverService;
use App\Services\MechanicService;
use App\Services\TripService;
use App\Services\ScheduledMaintenanceService;
use App\Services\UserService;
use App\Repositories\VehicleRepository;
use App\Repositories\DriverRepository;
use App\Repositories\MechanicRepository;
use App\Repositories\TripRepository;
use App\Repositories\ScheduledMaintenanceRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = require __DIR__ . '/../config/config.php';

\App\Logging\Logger::channel('app')->info('Sistema iniciado', [
    'env'  => $config['app']['env'] ?? 'production',
    'php'  => PHP_VERSION,
    'host' => $_SERVER['HTTP_HOST'] ?? 'cli',
]);

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

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $config['database']['connection'],
    'host'      => $config['database']['host'],
    'port'      => $config['database']['port'],
    'database'  => $config['database']['database'],
    'username'  => $config['database']['username'],
    'password'  => $config['database']['password'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// DI manual - Usuarios
$userRepository = new UserRepository();
$userService = new UserService($userRepository);
$userController = new UserController($userService);

// DI manual - Veiculos
$vehicleRepository = new VehicleRepository();
$vehicleService = new VehicleService($vehicleRepository);
$vehicleController = new VehicleController($vehicleService);

// DI manual - Motoristas
$driverRepository = new DriverRepository();
$driverService = new DriverService($driverRepository);
$driverController = new DriverController($driverService);

// DI manual - Mecanicos
$mechanicRepository = new MechanicRepository();
$mechanicService = new MechanicService($mechanicRepository);
$mechanicController = new MechanicController($mechanicService);

// DI manual - Viagens
$tripRepository = new TripRepository();
$tripService = new TripService($tripRepository);
$tripController = new TripController($tripService);

// DI manual - Manutencoes Programadas
$scheduledMaintenanceRepository = new ScheduledMaintenanceRepository();
$scheduledMaintenanceService = new ScheduledMaintenanceService($scheduledMaintenanceRepository);
$maintenanceController = new ScheduledMaintenanceController($scheduledMaintenanceService);

// DI manual - Relatorios
$reportRepository = new ReportRepository();
$reportService = new ReportService($reportRepository);
$reportController = new ReportController($reportService);

$homeController = new HomeController();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(
    (bool) ($config['app']['debug'] ?? false),
    true,
    true
);

$routes = require __DIR__ . '/../config/routes.php';
$routes($app, $vehicleController, $driverController, $homeController, $mechanicController, $tripController, $maintenanceController, $reportController, $userController);

// Fallback SPA: qualquer rota que não seja /api/* serve o index.html
// Permite que o Vue Router gerencie as rotas do frontend
$app->get('/[{params:.*}]', function ($request, $response) use ($homeController) {
    $path = $request->getUri()->getPath();
    if (str_starts_with($path, '/api/')) {
        $response->getBody()->write(json_encode(['error' => 'Not found']));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    return $homeController->index($request, $response);
});

$app->run();
