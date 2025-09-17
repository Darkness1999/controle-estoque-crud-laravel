<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Marca;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        // Busca os produtos e já carrega os relacionamentos de categoria e fornecedor
        $produtos = Produto::with('categoria', 'fornecedor')->latest()->get();

        return view('produtos.index', ['produtos' => $produtos]);
    }

    // Método para mostrar o FORMULÁRIO DE CRIAÇÃO
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();

        return view('produtos.create', compact('categorias', 'fornecedores', 'marcas'));
    }

    // Método para SALVAR um novo produto
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedors,id',
        ]);

        $produto = Produto::create($request->all());

        // Redireciona para a PÁGINA DE EDIÇÃO para gerenciar as variações
        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base criado com sucesso! Agora, adicione as variações.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // Método para mostrar o FORMULÁRIO DE EDIÇÃO
    public function edit(Produto $produto)
    {
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();

        return view('produtos.edit', compact('produto', 'categorias', 'fornecedores', 'marcas'));
    }

    // Método para ATUALIZAR um produto existente
    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedors,id',
        ]);

        $produto->update($request->all());

        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base atualizado com sucesso!');
    }

    // Método para DELETAR um produto
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('sucesso', 'Produto apagado com sucesso!');
    }
}
