<?php
/**
 * Definicao das rotas da aplicacao
 * 
 * @param Slim\App $app Instancia do Slim Framework
 * @param App\Controllers\VehicleController $vehicleController
 * @param App\Controllers\DriverController $driverController
 * @param App\Controllers\HomeController $homeController
 * @param App\Controllers\MechanicController $mechanicController
 * @param App\Controllers\TripController $tripController
 * @param App\Controllers\ScheduledMaintenanceController $maintenanceController
 */
return function ($app, $vehicleController, $driverController, $homeController, $mechanicController, $tripController, $maintenanceController) {
    // Rotas da API - Veiculos
    $app->get('/api/vehicles', [$vehicleController, 'index']);
    $app->get('/api/vehicles/{id}', [$vehicleController, 'show']);
    $app->post('/api/vehicles', [$vehicleController, 'store']);
    $app->put('/api/vehicles/{id}', [$vehicleController, 'update']);
    $app->delete('/api/vehicles/{id}', [$vehicleController, 'destroy']);

    // Rotas da API - Motoristas
    $app->get('/api/drivers', [$driverController, 'index']);
    $app->get('/api/drivers/{id}', [$driverController, 'show']);
    $app->post('/api/drivers', [$driverController, 'store']);
    $app->put('/api/drivers/{id}', [$driverController, 'update']);
    $app->delete('/api/drivers/{id}', [$driverController, 'destroy']);

    // Rotas da API - Mecanicos
    $app->get('/api/mechanics', [$mechanicController, 'index']);
    $app->get('/api/mechanics/{id}', [$mechanicController, 'show']);
    $app->post('/api/mechanics', [$mechanicController, 'store']);
    $app->put('/api/mechanics/{id}', [$mechanicController, 'update']);
    $app->delete('/api/mechanics/{id}', [$mechanicController, 'destroy']);

    // Rotas da API - Viagens
    $app->get('/api/trips', [$tripController, 'index']);
    $app->get('/api/trips/{id}', [$tripController, 'show']);
    $app->post('/api/trips', [$tripController, 'store']);
    $app->put('/api/trips/{id}', [$tripController, 'update']);
    $app->delete('/api/trips/{id}', [$tripController, 'destroy']);

    // Rotas da API - Manutencoes Programadas
    $app->get('/api/scheduled-maintenances', [$maintenanceController, 'index']);
    $app->get('/api/scheduled-maintenances/{id}', [$maintenanceController, 'show']);
    $app->post('/api/scheduled-maintenances', [$maintenanceController, 'store']);
    $app->put('/api/scheduled-maintenances/{id}', [$maintenanceController, 'update']);
    $app->delete('/api/scheduled-maintenances/{id}', [$maintenanceController, 'destroy']);
};
