<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteEstoque extends Model
{
    use HasFactory;

    protected $table = 'lote_estoques';

    protected $fillable = [
        'product_variation_id',
        'lote',
        'data_validade',
        'quantidade_atual',
    ];

    /**
     * Um lote pertence a uma Variação de Produto.
     */
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}