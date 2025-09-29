<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Marca;
use App\Models\Atributo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('categoria', 'marca', 'variations')->latest();
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $produtos = $query->paginate(15); // Mostra 15 produtos por página

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
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'codigo_barras' => 'nullable|string|max:255|unique:produtos',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('product_photos', 'public');
            $validatedData['foto_path'] = $path;
        }

        $produto = Produto::create($validatedData);

        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base criado! Agora, defina os atributos e adicione as variações.');
    }

    public function show(Produto $produto)
    {
        $produto->load('categoria', 'marca', 'fornecedor', 'variations.attributeValues.atributo');
        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        $produto->load('variations.attributeValues.atributo', 'attributes');
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $marcas = Marca::orderBy('nome')->get();
        $todosAtributos = Atributo::with('valorAtributos')->get();
        return view('produtos.edit', compact('produto', 'categorias', 'fornecedores', 'marcas', 'todosAtributos'));
    }

    public function update(Request $request, Produto $produto)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'codigo_barras' => 'nullable|string|max:255|unique:produtos,codigo_barras,' . $produto->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($produto->foto_path) {
                Storage::disk('public')->delete($produto->foto_path);
            }
            $path = $request->file('foto')->store('product_photos', 'public');
            $validatedData['foto_path'] = $path;
        }

        $produto->update($validatedData);

        if ($request->input('action') === 'save_and_back') {
            return redirect()->route('produtos.index')->with('sucesso', 'Produto atualizado com sucesso!');
        }
        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Produto base atualizado com sucesso!');
    }

    public function syncAttributes(Request $request, Produto $produto)
    {
        $request->validate([
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:atributos,id',
        ]);
        
        $attributes = $request->input('attributes', []);
        $produto->attributes()->sync($attributes);

        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Atributos do produto atualizados!');
    }

    public function destroy(Produto $produto)
    {
        if ($produto->foto_path) {
            Storage::disk('public')->delete($produto->foto_path);
        }
        $produto->delete();
        return redirect()->route('produtos.index')->with('sucesso', 'Produto apagado com sucesso!');
    }
}