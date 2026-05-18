<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Autenticação - Gerencia login/logout
 * Segue ISO 27001: sanitização de entrada e verificação timing-safe de senha
 */
class AuthController
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * POST /api/auth/login - Autentica usuário
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];
        $email = sanitize_input($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Email e senha são obrigatórios.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        $user = $this->userService->authenticate($email, $password);

        if ($user === null) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Credenciais inválidas.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        if (!$user->active) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Usuário inativo. Contate o administrador.',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }

        // Token simples (em produção usar JWT)
        $token = bin2hex(random_bytes(32));

        $payload = json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso.',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'level' => $user->level,
            ],
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
