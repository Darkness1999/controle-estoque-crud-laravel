<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'descricao', 'codigo_interno', 'sku',
        'estoque_atual', 'estoque_minimo', 'preco_custo', 'preco_venda',
        'categoria_id', 'fornecedor_id'
    ];

    // Um Produto PERTENCE A UMA Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Um Produto PERTENCE A UM Fornecedor
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}