<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Http\Request;

class ProdutoApiController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('categoria', 'marca')->paginate(15);
        return ProdutoResource::collection($produtos);
    }

    public function show(Produto $produto)
    {
        // Carrega todos os relacionamentos necessários para a visão detalhada
        $produto->load('categoria', 'marca', 'variations.attributeValues.atributo');
        return new ProdutoResource($produto);
    }
}