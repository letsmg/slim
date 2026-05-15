<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [\App\Controllers\HomeController::class, 'index']);

    $app->group('/api', function (RouteCollectorProxy $group) {
        // API routes will be added here
        $group->get('/users', function (Request $request, Response $response) {
            $response->getBody()->write(json_encode(['users' => []]));
            return $response->withHeader('Content-Type', 'application/json');
        });
    });
};