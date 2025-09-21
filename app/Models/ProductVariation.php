<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'sku',
        'preco_venda',
        'preco_custo',
        'estoque_minimo',
    ];

    /**
     * Uma Variação PERTENCE A UM Produto Base.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Uma Variação é definida por MUITOS Valores de Atributos (ex: Azul + M).
     */
    public function attributeValues()
    {
        return $this->belongsToMany(ValorAtributo::class, 'attribute_value_product_variation', 'product_variation_id', 'valor_atributo_id');
    }

    /**
     * Uma variação TEM MUITOS lotes de estoque.
     */
    public function lotesEstoque()
    {
        return $this->hasMany(LoteEstoque::class);
    }

    /**
     * O estoque total é agora a SOMA das quantidades de todos os seus lotes.
     */
    public function getEstoqueAtualAttribute()
    {
        return $this->lotesEstoque()->sum('quantidade_atual');
    }
}