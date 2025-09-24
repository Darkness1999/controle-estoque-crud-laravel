<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdutoApiController;
use App\Http\Controllers\Api\MovimentacaoApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\MarcaApiController;

// Rota pública para login na API
Route::post('/login', [AuthController::class, 'login']);

// Grupo de rotas que exigem autenticação via token Sanctum
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rotas para listar produtos (index e show)
    Route::apiResource('produtos', ProdutoApiController::class)->only(['index', 'show']);

    // Rota para registrar movimentações de estoque
    Route::post('/movimentacoes', [MovimentacaoApiController::class, 'store']);

    // Rota para listar categorias
    Route::get('/categorias', [CategoriaApiController::class, 'index']);

    // Rota para listar marcas
    Route::get('/marcas', [MarcaApiController::class, 'index']);
});