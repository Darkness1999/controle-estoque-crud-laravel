<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::latest()->paginate(10);
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:fornecedores',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'condicoes_pagamento' => 'nullable|string|max:255',
        ]);

        Fornecedor::create($request->all());
        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor criado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        $movimentacoes = $fornecedor->movimentacoes()
                                 ->where('tipo', 'entrada')
                                 ->with('productVariation.produto', 'productVariation.attributeValues.atributo')
                                 ->latest()
                                 ->paginate(10);

        return view('fornecedores.show', compact('fornecedor', 'movimentacoes'));
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:fornecedores,cnpj,' . $fornecedor->id,
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'condicoes_pagamento' => 'nullable|string|max:255',
        ]);

        $fornecedor->update($request->all());
        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor apagado com sucesso!');
    }
}