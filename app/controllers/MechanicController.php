<?php

namespace App\Controllers;

use App\Services\MechanicService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Mechanic - Gerencia requisições HTTP de mecânicos
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class MechanicController
{
    public function __construct(
        private MechanicService $service
    ) {}

    /**
     * GET /api/mechanics - Lista todos os mecânicos
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = sanitize_inputs($request->getQueryParams());
        $mechanics = $this->service->list($filters);

        $payload = json_encode([
            'success' => true,
            'mechanics' => $mechanics->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/mechanics/{id} - Busca mecânico por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $mechanic = $this->service->find($id);

        if (!$mechanic) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Mecânico não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'mechanic' => $mechanic->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/mechanics - Cria um novo mecânico
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $mechanic = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Mecânico cadastrado com sucesso',
                'mechanic' => $mechanic->toArray(),
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
     * PUT /api/mechanics/{id} - Atualiza um mecânico
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $mechanic = $this->service->find($id);

        if (!$mechanic) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Mecânico não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $mechanic = $this->service->update($mechanic, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Mecânico atualizado com sucesso',
                'mechanic' => $mechanic->toArray(),
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
     * DELETE /api/mechanics/{id} - Remove um mecânico
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $mechanic = $this->service->find($id);

        if (!$mechanic) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Mecânico não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($mechanic);

        $payload = json_encode([
            'success' => true,
            'message' => 'Mecânico removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
