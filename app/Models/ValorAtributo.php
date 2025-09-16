<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValorAtributo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'atributo_id',
        'valor',
    ];

    /**
     * Um Valor (ex: Azul) PERTENCE A UM Atributo (ex: Cor).
     */
    public function atributo()
    {
        return $this->belongsTo(Atributo::class);
    }

    /**
     * Um Valor (ex: Azul) PODE PERTENCER A MUITAS Variações de Produto.
     */
    public function productVariations()
    {
        return $this->belongsToMany(ProductVariation::class, 'attribute_value_product_variation', 'valor_atributo_id', 'product_variation_id');
    }
}