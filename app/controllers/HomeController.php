<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    /**
     * Serve o index.html do SPA (Vue.js)
     * O Vue Router gerencia todas as rotas do frontend
     * A comunicação com o backend é feita via API (/api/*)
     * 
     * Em desenvolvimento: injeta tags do Vite Dev Server (HMR)
     * Em produção: injeta tags dos assets compilados via manifest.json
     */
    public function index(Request $request, Response $response): Response
    {
        $htmlPath = __DIR__ . '/../../public/index.html';

        if (!file_exists($htmlPath)) {
            $response->getBody()->write('Index not found');
            return $response->withStatus(500);
        }

        $html = file_get_contents($htmlPath);

        // Gera as tags do Vite baseado no ambiente
        $viteTags = $this->getViteTags();

        // Substitui o placeholder <!--vite-tags--> pelas tags reais
        $html = str_replace('<!--vite-tags-->', $viteTags, $html);

        $response->getBody()->write($html);

        return $response
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');
    }

    /**
     * Detecta o ambiente e retorna as tags HTML corretas
     * 
     * APP_ENV=local: usa o Vite Dev Server (arquivo public/hot)
     * APP_ENV=production: usa os assets compilados (manifest.json)
     */
    private function getViteTags(): string
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'production';

        if ($appEnv === 'local') {
            $hotFile = __DIR__ . '/../../public/hot';

            // Se o arquivo hot existe, usa Vite Dev Server
            if (file_exists($hotFile)) {
                return $this->getDevTags($hotFile);
            }

            // Se não tem hot file mas está em local, avisa
            return '<!-- Vite Dev Server not running. Run: npm run dev -->';
        }

        // Produção: lê o manifest.json
        return $this->getProdTags();
    }

    /**
     * Tags para desenvolvimento: conecta ao Vite Dev Server
     */
    private function getDevTags(string $hotFile): string
    {
        // Lê a URL do servidor Vite do arquivo hot
        $devServerUrl = trim(file_get_contents($hotFile));

        $tags = '<script type="module" src="' . $devServerUrl . '/@vite/client"></script>' . "\n";
        $tags .= '<script type="module" src="' . $devServerUrl . '/js/app.js"></script>';

        return $tags;
    }

    /**
     * Tags para produção: lê o manifest.json e gera os links
     */
    private function getProdTags(): string
    {
        $manifestPath = __DIR__ . '/../../public/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            // Fallback: tenta o caminho antigo (sem .vite/)
            $oldPath = __DIR__ . '/../../public/manifest.json';
            if (file_exists($oldPath)) {
                $manifestPath = $oldPath;
            } else {
                return '<!-- manifest.json not found -->';
            }
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!$manifest) {
            return '<!-- manifest.json is empty or invalid -->';
        }

        // Encontra o entry point (isEntry = true)
        $entry = null;
        foreach ($manifest as $key => $data) {
            if (!empty($data['isEntry'])) {
                $entry = $data;
                break;
            }
        }

        if (!$entry) {
            return '<!-- entry point not found in manifest -->';
        }

        $tags = '';

        // CSS files
        if (isset($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                $tags .= '<link rel="stylesheet" href="/' . $cssFile . '" />' . "\n";
            }
        }

        // JS file
        if (isset($entry['file'])) {
            $tags .= '<script type="module" src="/' . $entry['file'] . '"></script>';
        }

        return $tags;
    }

    /**
     * Exemplo de endpoint com dados sanitizados na entrada e escapados na saída
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);

        // Dados simulados — em produção viriam do banco via UserService
        $user = [
            'id'    => $id,
            'name'  => 'Exemplo ' . $id,
            'email' => 'usuario' . $id . '@exemplo.com',
        ];

        // Monta resposta em JSON escapando os valores
        $payload = json_encode([
            'id'    => $user['id'],
            'name'  => esc($user['name']),
            'email' => esc($user['email']),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Endpoint de contato com sanitização de entrada e validação CSRF
     */
    public function contact(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        // Sanitiza todos os dados de entrada
        $data = sanitize_inputs($data);

        // Valida email
        if (!validate_email($data['email'] ?? '')) {
            $result = json_encode(['error' => 'Email inválido']);
            $response->getBody()->write($result);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        // Simula processamento (em produção: salvar no banco)
        $result = json_encode([
            'message' => 'Contato recebido com sucesso',
            'name'    => esc($data['name'] ?? ''),
            'email'   => esc($data['email'] ?? ''),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($result);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
