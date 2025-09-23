<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

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
        'foto_path'
    ];

    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function marca() { return $this->belongsTo(Marca::class); }
    public function fornecedor() { return $this->belongsTo(Fornecedor::class); }
    public function variations() { return $this->hasMany(ProductVariation::class); }
    public function attributes() { return $this->belongsToMany(Atributo::class, 'attribute_product', 'produto_id', 'atributo_id'); }
    public function getTotalStockAttribute() { return $this->variations()->with('lotesEstoque')->get()->sum(function($variation) { return $variation->lotesEstoque->sum('quantidade_atual'); }); }
    public function getPrecoMinimoAttribute() { return $this->variations()->min('preco_venda'); }
}