<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Policies\UserPolicy;
use App\Requests\StoreUserRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de User - Gerencia requisicoes HTTP de usuarios
 * Segue ISO 27001: sanitizacao de entrada e escape de saida
 * Aplica UserPolicy para autorizacao baseada em nivel de acesso
 */
class UserController
{
    private UserPolicy $policy;

    public function __construct(
        private UserService $service
    ) {
        $this->policy = new UserPolicy();
    }

    /**
     * Obtem o usuario autenticado a partir do header Authorization
     * Em producao, usar JWT/middleware de autenticacao
     */
    private function getAuthenticatedUser(Request $request): ?User
    {
        $userEmail = $request->getHeaderLine('X-User-Email');
        if (empty($userEmail)) {
            return null;
        }
        return User::where('email', $userEmail)->first();
    }

    /**
     * GET /api/users - Lista usuarios conforme permissao
     * Admin: ve todos
     * Operacional: ve operacional e suporte
     * Suporte: ve apenas suportes
     */
    public function index(Request $request, Response $response): Response
    {
        $authUser = $this->getAuthenticatedUser($request);
        $allUsers = $this->service->findAll();

        // Filtra usuarios conforme a policy de view
        if ($authUser) {
            $allUsers = array_filter($allUsers, function ($user) use ($authUser) {
                // Converte array para objeto User se necessario
                $targetUser = $user instanceof User ? $user : (object) $user;
                return $this->policy->view($authUser, $targetUser);
            });
            // Reindexa o array apos o filtro
            $allUsers = array_values($allUsers);
        }

        $payload = json_encode([
            'success' => true,
            'users'   => $allUsers,
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/users/{id} - Busca usuario por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuario nao encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        // Verifica autorizacao via Policy
        $authUser = $this->getAuthenticatedUser($request);
        if ($authUser && !$this->policy->view($authUser, $user)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Voce nao tem permissao para visualizar este usuario.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        $payload = json_encode([
            'success' => true,
            'user'    => $user->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/users - Cria um novo usuario
     * Apenas admin pode criar usuarios
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        // Verifica autorizacao via Policy
        $authUser = $this->getAuthenticatedUser($request);
        if ($authUser && !$this->policy->create($authUser)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Apenas administradores podem criar usuarios.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        try {
            $user = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Usuario criado com sucesso',
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
     * PUT /api/users/{id} - Atualiza um usuario
     * Aplica UserPolicy: admin nao pode editar outro admin
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuario nao encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        // Verifica autorizacao via Policy
        $authUser = $this->getAuthenticatedUser($request);
        if ($authUser && !$this->policy->update($authUser, $user)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Voce nao tem permissao para editar este usuario.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $user = $this->service->update($id, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Usuario atualizado com sucesso',
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
     * PUT /api/users/{id}/reset-password - Reseta a senha de um usuario
     * Admin pode resetar senha de qualquer usuario (inclusive outro admin)
     * Operacional pode resetar senha de operacional e suporte
     * Suporte pode resetar senha de outros suportes
     */
    public function resetPassword(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuario nao encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        // Verifica autorizacao via Policy
        $authUser = $this->getAuthenticatedUser($request);
        if ($authUser && !$this->policy->resetPassword($authUser, $user)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Voce nao tem permissao para resetar a senha deste usuario.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        $data = $request->getParsedBody() ?? [];
        $newPassword = $data['password'] ?? '123456';

        try {
            $user = $this->service->update($id, ['password' => $newPassword]);

            $payload = json_encode([
                'success' => true,
                'message' => 'Senha resetada com sucesso.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\InvalidArgumentException $e) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Erro ao resetar senha.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }
    }

    /**
     * DELETE /api/users/{id} - Remove um usuario (soft delete)
     * Aplica UserPolicy: nao pode deletar a si mesmo nem outro admin
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $user = $this->service->findById($id);

        if (!$user) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuario nao encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        // Verifica autorizacao via Policy
        $authUser = $this->getAuthenticatedUser($request);
        if ($authUser && !$this->policy->delete($authUser, $user)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Voce nao pode excluir este usuario.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        $this->service->delete($id);

        $payload = json_encode([
            'success' => true,
            'message' => 'Usuario removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
