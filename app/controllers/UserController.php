<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de User - Gerencia requisições HTTP de usuários
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class UserController
{
    public function __construct(
        private UserService $service
    ) {}

    /**
     * GET /api/users - Lista todos os usuários
     */
    public function index(Request $request, Response $response): Response
    {
        $users = $this->service->findAll();

        $payload = json_encode([
            'success' => true,
            'users'   => $users,
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/users/{id} - Busca usuário por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuário não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'user'    => $user->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/users - Cria um novo usuário
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $user = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Usuário criado com sucesso',
                'user'    => $user->toArray(),
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
     * PUT /api/users/{id} - Atualiza um usuário
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuário não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $user = $this->service->update($id, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Usuário atualizado com sucesso',
                'user'    => $user->toArray(),
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
     * DELETE /api/users/{id} - Remove um usuário (soft delete)
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuário não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($id);

        $payload = json_encode([
            'success' => true,
            'message' => 'Usuário removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
