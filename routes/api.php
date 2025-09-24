<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdutoApiController;
use App\Http\Controllers\Api\MovimentacaoApiController;

// Rota pública para login na API
Route::post('/login', [AuthController::class, 'login']);

// Grupo de rotas que exigem autenticação via token Sanctum
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 2. A NOSSA NOVA ROTA PROTEGIDA
    Route::get('/produtos', [ProdutoApiController::class, 'index']);

    // Rota para registrar movimentações de estoque
    Route::post('/movimentacoes', [MovimentacaoApiController::class, 'store']);
});