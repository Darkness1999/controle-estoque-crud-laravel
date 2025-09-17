<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\AtributoController;
use App\Http\Controllers\ValorAtributoController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\MovimentacaoEstoqueController;

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

    Route::resource('atributos', AtributoController::class)
        ->parameters(['atributos' => 'atributo'])
        ->middleware('auth');

    // Rota para o CRUD de Atributos (Pai)
    Route::resource('atributos', AtributoController::class)
        ->parameters(['atributos' => 'atributo']);
            
    // Rotas aninhadas para os Valores de Atributo (Filhos)
    Route::get('atributos/{atributo}/valores', [ValorAtributoController::class, 'index'])   ->name('valores.index');
    Route::get('atributos/{atributo}/valores/create', [ValorAtributoController::class, 'create'])->name('valores.create');
    Route::post('atributos/{atributo}/valores', [ValorAtributoController::class, 'store'])->name('valores.store');
    Route::delete('valores/{valorAtributo}', [ValorAtributoController::class, 'destroy'])->name('valores.destroy');

    // Rotas para criar e apagar variações de um produto específico
    Route::get('variations/{variation}/edit', [ProductVariationController::class, 'edit'])->name('variations.edit');
    Route::put('variations/{variation}', [ProductVariationController::class, 'update'])->name('variations.update');
    Route::post('produtos/{produto}/variations', [ProductVariationController::class, 'store'])->name('variations.store');
    Route::delete('variations/{variation}', [ProductVariationController::class, 'destroy'])->name('variations.destroy');

    Route::get('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'index'])->name('estoque.movimentar');
    Route::post('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'store'])->name('estoque.store');
});

require __DIR__.'/auth.php';
