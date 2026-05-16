<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements MiddlewareInterface
{
    private const SESSION_TOKEN_KEY = 'auth_token';
    private const SESSION_USER_KEY = 'auth_user';

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Verifica se o token CSRF está presente em requisições que alteram estado
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->validateCsrfToken($request);
        }

        // Verifica se o usuário está autenticado
        if (!$this->isAuthenticated()) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                'error' => 'Não autenticado',
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        // Adiciona headers de segurança na resposta
        $response = $handler->handle($request);
        $response = $this->addSecurityHeaders($response);

        return $response;
    }

    /**
     * Verifica se o usuário está autenticado via sessão
     */
    private function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION[self::SESSION_USER_KEY])
            && isset($_SESSION[self::SESSION_TOKEN_KEY]);
    }

    /**
     * Valida o token CSRF para requisições que alteram estado
     */
    private function validateCsrfToken(Request $request): void
    {
        $csrfToken = $request->getHeaderLine('X-CSRF-Token')
            ?? $request->getParsedBody()['_csrf_token']
            ?? '';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $storedToken = $_SESSION['csrf_token'] ?? '';

        if (empty($storedToken) || !hash_equals($storedToken, $csrfToken)) {
            throw new \RuntimeException('Token CSRF inválido');
        }
    }

    /**
     * Adiciona headers de segurança na resposta
     */
    private function addSecurityHeaders(Response $response): Response
    {
        return $response
            // Protege contra MIME sniffing
            ->withHeader('X-Content-Type-Options', 'nosniff')
            // Protege contra clickjacking
            ->withHeader('X-Frame-Options', 'DENY')
            // Proteção XSS para navegadores antigos
            ->withHeader('X-XSS-Protection', '1; mode=block')
            // Referrer Policy
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            // HSTS (apenas em produção)
            ->withAddedHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            // Content Security Policy básica
            ->withHeader('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'");
    }

    /**
     * Gera e armazena token CSRF na sessão
     */
    public static function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = generate_secure_token();
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Define o usuário na sessão (chamado após login)
     */
    public static function setAuthenticatedUser(int $userId, string $token): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION[self::SESSION_USER_KEY] = $userId;
        $_SESSION[self::SESSION_TOKEN_KEY] = $token;
    }

    /**
     * Remove autenticação da sessão (logout)
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION[self::SESSION_USER_KEY]);
        unset($_SESSION[self::SESSION_TOKEN_KEY]);

        // Regenera ID da sessão para prevenir session fixation
        session_regenerate_id(true);
    }
}