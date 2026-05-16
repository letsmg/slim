<?php

namespace App\Controllers;

use App\Services\TripService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Trip - Gerencia requisições HTTP de viagens
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class TripController
{
    public function __construct(
        private TripService $service
    ) {}

    /**
     * GET /api/trips - Lista todas as viagens
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = sanitize_inputs($request->getQueryParams());
        $trips = $this->service->list($filters);

        $payload = json_encode([
            'success' => true,
            'trips' => $trips->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/trips/{id} - Busca viagem por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $trip = $this->service->find($id);

        if (!$trip) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Viagem não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'trip' => $trip->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/trips - Cria uma nova viagem
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $trip = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Viagem cadastrada com sucesso',
                'trip' => $trip->toArray(),
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
     * PUT /api/trips/{id} - Atualiza uma viagem
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $trip = $this->service->find($id);

        if (!$trip) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Viagem não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $trip = $this->service->update($trip, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Viagem atualizada com sucesso',
                'trip' => $trip->toArray(),
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
     * DELETE /api/trips/{id} - Remove uma viagem
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $trip = $this->service->find($id);

        if (!$trip) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Viagem não encontrada',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($trip);

        $payload = json_encode([
            'success' => true,
            'message' => 'Viagem removida com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
