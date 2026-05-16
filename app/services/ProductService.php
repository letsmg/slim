<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Requests\StoreProductRequest;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service de Product - Camada de lógica de negócio
 * Orquestra validação, autorização e acesso a dados
 */
class ProductService
{
    public function __construct(
        private ProductRepository $repository,
        private StoreProductRequest $request
    ) {}

    /**
     * Lista todos os produtos com filtros
     * 
     * @param array $filters Filtros opcionais (active, category, search)
     * @return Collection
     */
    public function list(array $filters = []): Collection
    {
        return $this->repository->findAll($filters);
    }

    /**
     * Busca um produto por ID
     * 
     * @param int $id
     * @return Product|null
     */
    public function find(int $id): ?Product
    {
        return $this->repository->findById($id);
    }

    /**
     * Cria um novo produto com validação
     * 
     * @param array $data Dados do produto
     * @return Product
     * @throws \InvalidArgumentException
     */
    public function create(array $data): Product
    {
        $validated = $this->request->validated($data);
        return $this->repository->create($validated);
    }

    /**
     * Atualiza um produto existente com validação
     * 
     * @param Product $product
     * @param array $data Dados atualizados
     * @return Product
     * @throws \InvalidArgumentException
     */
    public function update(Product $product, array $data): Product
    {
        $validated = $this->request->validated($data);
        $this->repository->update($product, $validated);
        return $product->fresh();
    }

    /**
     * Remove um produto (soft delete)
     * 
     * @param Product $product
     * @return bool
     */
    public function delete(Product $product): bool
    {
        return $this->repository->delete($product) !== false;
    }

    /**
     * Retorna estatísticas dos produtos
     * 
     * @return array
     */
    public function getStats(): array
    {
        return $this->repository->getStats();
    }
}