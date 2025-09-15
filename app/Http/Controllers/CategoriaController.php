<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        // Pega todas as categorias do banco de dados usando o Model
        $categorias = Categoria::all();

        // Retorna a view (tela) e envia a lista de categorias para ela
        return view('categorias.index', ['categorias' => $categorias]);
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        // 2. Salvar no banco de dados
        Categoria::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
        ]);

        // 3. Redirecionar para a página de listagem
        return redirect()->route('categorias.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Categoria $categoria)
        {
            return view('categorias.edit', ['categoria' => $categoria]);
        }

    public function update(Request $request, Categoria $categoria)
        {
            // 1. Validação dos dados (mesmas regras do store)
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string',
            ]);

            // 2. Atualizar os dados da categoria no banco
            $categoria->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
            ]);

            // 3. Redirecionar para a página de listagem
            return redirect()->route('categorias.index')->with('sucesso', 'Categoria atualizada com sucesso!');
        }

        public function destroy(Categoria $categoria)
        {
            // Apaga a categoria do banco de dados
            $categoria->delete();

            // Redireciona de volta para a lista com uma mensagem de sucesso
            return redirect()->route('categorias.index')->with('sucesso', 'Categoria apagada com sucesso!');
        }
}
