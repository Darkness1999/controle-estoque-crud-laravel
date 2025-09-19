<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'categoria_id',
        'marca_id',
        'fornecedor_id',
        'codigo_barras',
        'foto_path',
    ];

    /**
     * Um Produto PERTENCE A UMA Categoria.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Um Produto PERTENCE A UMA Marca.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Um Produto PERTENCE A UM Fornecedor.
     */
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    /**
     * Um Produto TEM MUITAS Variações (SKUs).
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->variations()->sum('estoque_atual');
    }

    public function getPrecoMinimoAttribute()
    {
        // Pega o valor mínimo da coluna 'preco_venda' das variações relacionadas
        return $this->variations()->min('preco_venda');
    }

    /**
     * Um Produto pode ter MUITOS Atributos.
     */
    public function attributes()
    {
        return $this->belongsToMany(Atributo::class, 'attribute_product', 'produto_id', 'atributo_id');
    }
}