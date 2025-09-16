<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'endereco',
    ];

    // Um Fornecedor TEM MUITOS Produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
