<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
    ];

    /**
     * Um Atributo (ex: Cor) TEM MUITOS Valores (ex: Azul, Verde).
     */
    public function valorAtributos()
    {
        return $this->hasMany(ValorAtributo::class);
    }

    /**
     * Um Atributo pode pertencer a MUITOS Produtos.
     */
    public function products()
    {
        return $this->belongsToMany(Produto::class, 'attribute_product', 'atributo_id', 'produto_id');
    }
}