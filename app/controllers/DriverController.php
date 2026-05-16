<?php

namespace App\Controllers;

use App\Services\DriverService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Driver - Gerencia requisições HTTP de motoristas
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class DriverController
{
    public function __construct(
        private DriverService $service
    ) {}

    /**
     * GET /api/drivers - Lista todos os motoristas
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = sanitize_inputs($request->getQueryParams());
        $drivers = $this->service->list($filters);

        $payload = json_encode([
            'success' => true,
            'drivers' => $drivers->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/drivers/{id} - Busca motorista por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $driver = $this->service->find($id);

        if (!$driver) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Motorista não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'driver' => $driver->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/drivers - Cria um novo motorista
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $driver = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Motorista cadastrado com sucesso',
                'driver' => $driver->toArray(),
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
     * PUT /api/drivers/{id} - Atualiza um motorista
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $driver = $this->service->find($id);

        if (!$driver) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Motorista não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $driver = $this->service->update($driver, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Motorista atualizado com sucesso',
                'driver' => $driver->toArray(),
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
     * DELETE /api/drivers/{id} - Remove um motorista
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $driver = $this->service->find($id);

        if (!$driver) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Motorista não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($driver);

        $payload = json_encode([
            'success' => true,
            'message' => 'Motorista removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
