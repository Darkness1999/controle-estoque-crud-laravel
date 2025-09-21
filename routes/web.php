<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});

// Rota para o dashboard, protegida por autenticação e verificação de email
Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rota para o CRUD de Categorias com middleware de autenticação
    Route::resource('categorias', CategoriaController::class)->middleware('auth');

    // Rota para o CRUD de Fornecedores com middleware de autenticação
    Route::resource('fornecedores', FornecedorController::class)
        ->parameters(['fornecedores' => 'fornecedor'])
        ->middleware('auth');

    // Rota para o CRUD de Produtos com middleware de autenticação
    Route::resource('produtos', ProdutoController::class)
        ->parameters(['produtos' => 'produto'])
        ->middleware('auth');

    // Rota para o CRUD de Marcas com middleware de autenticação
    Route::resource('marcas', MarcaController::class)
        ->parameters(['marcas' => 'marca'])
        ->middleware('auth');

    // Rota para o CRUD de Atributos (Pai) com middleware de autenticação
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

    // Rotas para movimentação de estoque
    Route::get('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'index'])->name('estoque.movimentar');
    Route::post('/movimentar-estoque', [MovimentacaoEstoqueController::class, 'store'])->name('estoque.store');

    // Rota para relatórios de movimentações
    Route::get('/relatorios/movimentacoes', [RelatorioController::class, 'movimentacoes'])->name('relatorios.movimentacoes');

    // Agrupa as rotas de gestão de utilizadores e protege-as com o nosso Gate. 
    // -Como funciona: middleware('can:access-admin-area') aplica o nosso Gate a todas as rotas dentro do grupo. Se um utilizador não-admin tentar aceder a qualquer uma delas, o Laravel irá automaticamente bloqueá-lo e mostrar uma página de erro "403 | AÇÃO NÃO AUTORIZADA".
    Route::middleware('can:access-admin-area')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });

    // Rota para sincronizar atributos de um produto específico
    Route::put('produtos/{produto}/attributes', [ProdutoController::class, 'syncAttributes'])->name('produtos.attributes.sync');

    // Rota para o CRUD de Clientes com middleware de autenticação
    Route::resource('clientes', ClienteController::class)
    ->parameters(['clientes' => 'cliente'])
    ->middleware('auth');

    // Rota para imprimir etiquetas de variações de produtos
    Route::get('variations/{variation}/label', [ProductVariationController::class, 'printLabel'])->name('variations.label');
});

require __DIR__.'/auth.php';
