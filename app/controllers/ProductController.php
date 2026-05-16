<?php

namespace App\Controllers;

use App\Services\ProductService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller de Product - Gerencia requisições HTTP de produtos
 * Segue ISO 27001: sanitização de entrada e escape de saída
 */
class ProductController
{
    public function __construct(
        private ProductService $service
    ) {}

    /**
     * GET /api/products - Lista todos os produtos
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $filters = $request->getQueryParams();
        $filters = sanitize_inputs($filters);

        $products = $this->service->list($filters);

        $payload = json_encode([
            'success'  => true,
            'products' => $products->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/products/{id} - Busca produto por ID
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $product = $this->service->find($id);

        if (!$product) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Produto não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode([
            'success' => true,
            'product' => $product->toArray(),
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * POST /api/products - Cria um novo produto
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        try {
            $product = $this->service->create($data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Produto criado com sucesso',
                'product' => $product->toArray(),
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
     * PUT /api/products/{id} - Atualiza um produto
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $product = $this->service->find($id);

        if (!$product) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Produto não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $data = $request->getParsedBody() ?? [];

        try {
            $product = $this->service->update($product, $data);

            $payload = json_encode([
                'success' => true,
                'message' => 'Produto atualizado com sucesso',
                'product' => $product->toArray(),
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
     * DELETE /api/products/{id} - Remove um produto (soft delete)
     * 
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $product = $this->service->find($id);

        if (!$product) {
            $payload = json_encode([
                'success' => false,
                'message' => 'Produto não encontrado',
            ], JSON_UNESCAPED_UNICODE);

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->service->delete($product);

        $payload = json_encode([
            'success' => true,
            'message' => 'Produto removido com sucesso',
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * GET /api/products/stats - Estatísticas dos produtos
     * 
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function stats(Request $request, Response $response): Response
    {
        $stats = $this->service->getStats();

        $payload = json_encode([
            'success' => true,
            'stats'   => $stats,
        ], JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}