<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    // Método para LISTAR todos os fornecedores
    public function index()
    {
        $fornecedores = Fornecedor::all();
        return view('fornecedores.index', ['fornecedores' => $fornecedores]);
    }

    // Método para mostrar o FORMULÁRIO DE CRIAÇÃO
    public function create()
    {
        return view('fornecedores.create');
    }

    // Método para SALVAR um novo fornecedor
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:fornecedores',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        Fornecedor::create($request->all());

        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // Método para mostrar o FORMULÁRIO DE EDIÇÃO
    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', ['fornecedor' => $fornecedor]);
    }

    // Método para ATUALIZAR um fornecedor
    public function update(Request $request, Fornecedor $fornecedor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:fornecedores,cnpj,' . $fornecedor->id,
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        $fornecedor->update($request->all());

        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    // Método para APAGAR um fornecedor
    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor apagado com sucesso!');
    }
}
