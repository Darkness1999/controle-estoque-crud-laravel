<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Fornecedor;
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
        // Busca todas as categorias e fornecedores para popular os <select> no formulário
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('produtos.create', compact('categorias', 'fornecedores'));
    }

    // Método para SALVAR um novo produto
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'codigo_interno' => 'nullable|string|max:255|unique:produtos',
            'sku' => 'nullable|string|max:255|unique:produtos',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'nullable|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'fornecedor_id' => 'nullable|exists:fornecedors,id',
        ]);

        Produto::create($request->all());

        return redirect()->route('produtos.index')->with('sucesso', 'Produto criado com sucesso!');
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
            // Busca todas as categorias e fornecedores para popular os <select>
            $categorias = Categoria::orderBy('nome')->get();
            $fornecedores = Fornecedor::orderBy('nome')->get();

            // Retorna a view de edição, passando o produto e as listas
            return view('produtos.edit', compact('produto', 'categorias', 'fornecedores'));
        }

    // Método para ATUALIZAR um produto existente
        public function update(Request $request, Produto $produto)
        {
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'codigo_interno' => 'nullable|string|max:255|unique:produtos,codigo_interno,' . $produto->id,
                'sku' => 'nullable|string|max:255|unique:produtos,sku,' . $produto->id,
                'estoque_atual' => 'required|integer|min:0',
                'estoque_minimo' => 'nullable|integer|min:0',
                'preco_custo' => 'nullable|numeric|min:0',
                'preco_venda' => 'nullable|numeric|min:0',
                'categoria_id' => 'required|exists:categorias,id',
                'fornecedor_id' => 'nullable|exists:fornecedors,id',
            ]);

            $produto->update($request->all());

            return redirect()->route('produtos.index')->with('sucesso', 'Produto atualizado com sucesso!');
        }

    // Método para DELETAR um produto
    public function destroy(Produto $produto)
        {
            $produto->delete();
            return redirect()->route('produtos.index')->with('sucesso', 'Produto apagado com sucesso!');
        }
}
