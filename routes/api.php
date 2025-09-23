<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rota pública para login na API
Route::post('/login', [AuthController::class, 'login']);

// Grupo de rotas que exigem autenticação via token Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});