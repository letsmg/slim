<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Product - Representa um produto no sistema
 * 
 * Segue ISO 27001: validação de dados na camada Request,
 * saída escapada nas views, fillable para mass assignment
 */
class Product extends Model
{
    use SoftDeletes;

    /**
     * Nome da tabela no banco de dados
     * @var string
     */
    protected $table = 'products';

    /**
     * Atributos que podem ser preenchidos em massa (mass assignment)
     * Proteção contra Mass Assignment (OWASP)
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'active',
        'stock',
    ];

    /**
     * Atributos que devem ser convertidos para tipos nativos
     * @var array
     */
    protected $casts = [
        'price'     => 'decimal:2',
        'active'    => 'boolean',
        'stock'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Escopo para buscar apenas produtos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Escopo para filtrar por categoria
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Acessor: formata o preço no padrão brasileiro
     */
    public function getPriceFormattedAttribute(): string
    {
        return 'R$ ' . number_format((float) $this->price, 2, ',', '.');
    }

    /**
     * Mutator: sanitiza o nome ao salvar (strip tags + trim)
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = sanitize_input($value);
    }

    /**
     * Mutator: sanitiza a descrição ao salvar
     */
    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = $value ? sanitize_input($value) : null;
    }
}