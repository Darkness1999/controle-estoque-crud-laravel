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
}