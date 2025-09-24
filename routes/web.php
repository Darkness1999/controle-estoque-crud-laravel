<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\AtributoController;
use App\Http\Controllers\ValorAtributoController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\MovimentacaoEstoqueController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota pública de boas-vindas
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Rotas que Exigem Autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboards
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/vendas/dashboard', function () {
        return view('vendas.dashboard');
    })->name('vendas.dashboard');

    // Perfil do Utilizador
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUDs
    Route::resource('categorias', CategoriaController::class);
    Route::resource('marcas', MarcaController::class)->parameters(['marcas' => 'marca']);
    Route::resource('fornecedores', FornecedorController::class)->parameters(['fornecedores' => 'fornecedor']);
    Route::resource('clientes', ClienteController::class)->parameters(['clientes' => 'cliente']);
    Route::resource('atributos', AtributoController::class)->parameters(['atributos' => 'atributo']);
    Route::resource('produtos', ProdutoController::class)->parameters(['produtos' => 'produto']);

    // Rotas de Atributos e Variações
    Route::get('atributos/{atributo}/valores', [ValorAtributoController::class, 'index'])->name('valores.index');
    Route::get('atributos/{atributo}/valores/create', [ValorAtributoController::class, 'create'])->name('valores.create');
    Route::post('atributos/{atributo}/valores', [ValorAtributoController::class, 'store'])->name('valores.store');
    Route::delete('valores/{valorAtributo}', [ValorAtributoController::class, 'destroy'])->name('valores.destroy');
    Route::put('produtos/{produto}/attributes', [ProdutoController::class, 'syncAttributes'])->name('produtos.attributes.sync');
    Route::post('produtos/{produto}/variations', [ProductVariationController::class, 'store'])->name('variations.store');
    Route::get('variations/{variation}/edit', [ProductVariationController::class, 'edit'])->name('variations.edit');
    Route::put('variations/{variation}', [ProductVariationController::class, 'update'])->name('variations.update');
    Route::delete('variations/{variation}', [ProductVariationController::class, 'destroy'])->name('variations.destroy');
    Route::get('variations/{variation}/label', [ProductVariationController::class, 'printLabel'])->name('variations.label');

    // Funcionalidades Principais
    Route::get('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'index'])->name('estoque.movimentar');
    Route::post('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'store'])->name('estoque.store');
    Route::get('/relatorios/movimentacoes', [RelatorioController::class, 'movimentacoes'])->name('relatorios.movimentacoes');

    // Rotas de Administração
    Route::middleware('can:access-admin-area')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });
});

// Rotas de Autenticação
require __DIR__.'/auth.php';