<?php

namespace App\Controllers;

use App\Services\VehicleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Vehicle - Gerencia requisições HTTP de veículos
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class VehicleController
{
    public function __construct(
        private VehicleService $service
    ) {}

    /**
     * GET /api/vehicles - Lista todos os veículos
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = sanitize_inputs($request->getQueryParams());
        $vehicles = $this->service->list($filters);

        $payload = json_encode([
            'success' => true,
            'vehicles' => $vehicles->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/vehicles/{id} - Busca veículo por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $vehicle = $this->service->find($id);

        if (!$vehicle) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Veículo não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'vehicle' => $vehicle->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/vehicles - Cria um novo veículo
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $vehicle = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Veículo cadastrado com sucesso',
                'vehicle' => $vehicle->toArray(),
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\InvalidArgumentException $e) {
            $errorData = json_decode($e->getMessage(), true);

            $payload = json_encode([
                'success' => false,
                'errors'  => $errorData['errors'] ?? [$e->getMessage()],
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }
    }

    /**
     * PUT /api/vehicles/{id} - Atualiza um veículo
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $vehicle = $this->service->find($id);

        if (!$vehicle) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Veículo não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $vehicle = $this->service->update($vehicle, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Veículo atualizado com sucesso',
                'vehicle' => $vehicle->toArray(),
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\InvalidArgumentException $e) {
            $errorData = json_decode($e->getMessage(), true);

            $payload = json_encode([
                'success' => false,
                'errors'  => $errorData['errors'] ?? [$e->getMessage()],
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }
    }

    /**
     * DELETE /api/vehicles/{id} - Remove um veículo
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $vehicle = $this->service->find($id);

        if (!$vehicle) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Veículo não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($vehicle);

        $payload = json_encode([
            'success' => true,
            'message' => 'Veículo removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
