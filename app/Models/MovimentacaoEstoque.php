<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    protected $table = 'movimentacao_estoques'; // Especifica o nome da tabela manualmente

    protected $fillable = [
        'product_variation_id',
        'user_id',
        'tipo',
        'quantidade',
        'motivo',
        'observacao',
    ];

    // Uma movimentação pertence a uma Variação de Produto
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    // Uma movimentação foi feita por um Usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}