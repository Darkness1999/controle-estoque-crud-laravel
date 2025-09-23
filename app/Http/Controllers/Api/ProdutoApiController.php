<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Http\Request;

class ProdutoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca os produtos com os seus relacionamentos para evitar consultas extras
        $produtos = Produto::with('categoria', 'marca')->paginate(15);

        // Retorna uma coleção de produtos formatada pelo nosso Resource
        return ProdutoResource::collection($produtos);
    }
}