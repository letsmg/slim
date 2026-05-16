<?php

namespace App\Controllers;

use App\Services\ScheduledMaintenanceService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de ScheduledMaintenance - Gerencia requisições HTTP de manutenções programadas
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class ScheduledMaintenanceController
{
    public function __construct(
        private ScheduledMaintenanceService $service
    ) {}

    /**
     * GET /api/scheduled-maintenances - Lista todas as manutenções programadas
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = sanitize_inputs($request->getQueryParams());
        $maintenances = $this->service->list($filters);

        $payload = json_encode([
            'success' => true,
            'scheduled_maintenances' => $maintenances->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/scheduled-maintenances/{id} - Busca manutenção por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $maintenance = $this->service->find($id);

        if (!$maintenance) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Manutenção não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'scheduled_maintenance' => $maintenance->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/scheduled-maintenances - Cria uma nova manutenção programada
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $maintenance = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Manutenção cadastrada com sucesso',
                'scheduled_maintenance' => $maintenance->toArray(),
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
     * PUT /api/scheduled-maintenances/{id} - Atualiza uma manutenção programada
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $maintenance = $this->service->find($id);

        if (!$maintenance) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Manutenção não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $maintenance = $this->service->update($maintenance, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Manutenção atualizada com sucesso',
                'scheduled_maintenance' => $maintenance->toArray(),
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
     * DELETE /api/scheduled-maintenances/{id} - Remove uma manutenção programada
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $maintenance = $this->service->find($id);

        if (!$maintenance) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Manutenção não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($maintenance);

        $payload = json_encode([
            'success' => true,
            'message' => 'Manutenção removida com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
