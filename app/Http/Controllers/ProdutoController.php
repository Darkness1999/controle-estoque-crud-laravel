<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Marca;
use App\Models\Atributo;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('categoria', 'marca', 'variations')->latest();

        // Lógica do Filtro
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $produtos = $query->get();

        // Usado para mostrar o nome da categoria no título quando filtrado
        $categoriaFiltro = $request->filled('categoria_id') ? Categoria::find($request->categoria_id) : null;

        return view('produtos.index', compact('produtos', 'categoriaFiltro'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();
        return view('produtos.create', compact('categorias', 'fornecedores', 'marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
        ]);

        $produto = Produto::create($request->all());

        // A LÓGICA CORRETA DE REDIRECIONAMENTO
        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base criado! Agora adicione as variações.');
    }

    public function show(Produto $produto)
    {
        $produto->load('categoria', 'marca', 'fornecedor', 'variations.attributeValues.atributo');
        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        $produto->load('variations.attributeValues.atributo');
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();
        $atributos = Atributo::with('valorAtributos')->get();
        
        return view('produtos.edit', compact('produto', 'categorias', 'fornecedores', 'marcas', 'atributos'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
        ]);

        $produto->update($request->all());

        if ($request->input('action') === 'save_and_back') {
            return redirect()->route('produtos.index')->with('sucesso', 'Produto atualizado com sucesso!');
        }
        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produtos.index')->with('sucesso', 'Produto apagado com sucesso!');
    }
}