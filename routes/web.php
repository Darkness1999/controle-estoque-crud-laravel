<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categorias', CategoriaController::class)->middleware('auth');


Route::resource('fornecedores', FornecedorController::class)
    ->parameters(['fornecedores' => 'fornecedor'])
    ->middleware('auth');

Route::resource('produtos', ProdutoController::class)
    ->parameters(['produtos' => 'produto'])
    ->middleware('auth');

Route::resource('marcas', MarcaController::class)
    ->parameters(['marcas' => 'marca'])
    ->middleware('auth');

require __DIR__.'/auth.php';
