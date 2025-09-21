<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;
    protected $table = 'movimentacao_estoques';
    
    protected $fillable = [
        'product_variation_id',
        'user_id',
        'tipo',
        'quantidade',
        'motivo',
        'observacao',
        'fornecedor_id',
        'cliente_id',
    ];

    public function productVariation() 
    { 
        return $this->belongsTo(ProductVariation::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function fornecedor() 
    { 
        return $this->belongsTo(Fornecedor::class); 
    }

    
    public function cliente() 
    { 
        return $this->belongsTo(Cliente::class); 
    }

    /**
     * Uma movimentação pode estar associada a um lote específico.
     */
    public function loteEstoque()
    {
        return $this->belongsTo(LoteEstoque::class);
    }
}