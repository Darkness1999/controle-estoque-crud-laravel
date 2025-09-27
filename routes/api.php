<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdutoApiController;
use App\Http\Controllers\Api\CategoriaApiController;
use App\Http\Controllers\Api\MarcaApiController;
use App\Http\Controllers\Api\MovimentacaoApiController;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\FornecedorApiController;

// Rota pública para login na API
Route::post('/login', [AuthController::class, 'login']);

// Grupo de rotas que exigem autenticação via token Sanctum
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rotas de Consulta
    Route::apiResource('produtos', ProdutoApiController::class)->only(['index', 'show']);
    Route::get('/categorias', [CategoriaApiController::class, 'index']);
    Route::get('/marcas', [MarcaApiController::class, 'index']);
    Route::get('/clientes', [ClienteApiController::class, 'index']);
    Route::get('/fornecedores', [FornecedorApiController::class, 'index']);

    // Rota Operacional
    Route::post('/movimentacoes', [MovimentacaoApiController::class, 'store']);
});