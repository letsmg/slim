<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository de Product - Camada de acesso a dados
 * Abstrai a lógica de consultas e isolamento do banco
 */
class ProductRepository
{
    /**
     * Retorna todos os produtos (com filtros opcionais)
     * 
     * @param array $filters Filtros: active, category, search
     * @return Collection
     */
    public function findAll(array $filters = []): Collection
    {
        $query = Product::query();

        if (isset($filters['active'])) {
            $query->where('active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['category'])) {
            $query->byCategory(sanitize_input($filters['category']));
        }

        if (!empty($filters['search'])) {
            $search = sanitize_input($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Busca produto por ID
     * 
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Cria um novo produto
     * 
     * @param array $data Dados validados e sanitizados
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Atualiza um produto existente
     * 
     * @param Product $product
     * @param array $data Dados validados e sanitizados
     * @return bool
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Remove (soft delete) um produto
     * 
     * @param Product $product
     * @return bool|null
     */
    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    /**
     * Retorna estatísticas dos produtos
     * 
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total'        => Product::count(),
            'active'       => Product::where('active', true)->count(),
            'inactive'     => Product::where('active', false)->count(),
            'total_stock'  => Product::sum('stock'),
            'avg_price'    => Product::avg('price'),
        ];
    }
}