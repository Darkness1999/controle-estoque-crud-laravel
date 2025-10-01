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
use App\Http\Controllers\Api\ProductVariationApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\AtributoApiController;
use App\Http\Controllers\Api\ValorAtributoApiController;

// Rota pública para login na API
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Grupo de rotas que exigem autenticação via token Sanctum
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rotas de Consulta
    Route::apiResource('produtos', ProdutoApiController::class);

    Route::apiResource('categorias', CategoriaApiController::class);

    Route::apiResource('marcas', MarcaApiController::class);

    Route::apiResource('atributos', AtributoApiController::class);

    Route::apiResource('clientes', ClienteApiController::class);
    
    Route::apiResource('fornecedores', FornecedorApiController::class);

    // Rota Operacional
    Route::apiResource('movimentacoes', MovimentacaoApiController::class)->only(['index', 'store', 'show']);

    // --- NOVAS ROTAS PARA GESTÃO DE VARIAÇÕES ---
    Route::get('/produtos/{produto}/variations', [ProductVariationApiController::class, 'index']);
    Route::post('/produtos/{produto}/variations', [ProductVariationApiController::class, 'store']);
    Route::put('/variations/{variation}', [ProductVariationApiController::class, 'update']);
    Route::delete('/variations/{variation}', [ProductVariationApiController::class, 'destroy']);

    Route::apiResource('atributos.valores', ValorAtributoApiController::class)->except(['show'])->shallow();

    // --- Ações Específicas ---
    Route::put('/variations/{variation}', [ProductVariationApiController::class, 'update']);
    Route::delete('/variations/{variation}', [ProductVariationApiController::class, 'destroy']);
    
    // --- A NOSSA NOVA ROTA DE BUSCA OTIMIZADA ---
    Route::get('/search-by-code/{code}', [SearchApiController::class, 'searchByCode']);
});