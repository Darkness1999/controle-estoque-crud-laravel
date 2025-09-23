<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'nullable|string|max:18|unique:clientes',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente criado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        // Carrega as movimentações do cliente, ordenadas da mais recente para a mais antiga
        // e já inclui os detalhes da variação do produto para cada movimento.
        $movimentacoes = $cliente->movimentacoes()
                                ->with('productVariation.produto', 'productVariation.attributeValues.atributo')
                                ->latest()
                                ->paginate(10); // Paginamos para o caso de o cliente ter muitos pedidos

        return view('clientes.show', compact('cliente', 'movimentacoes'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'nullable|string|max:18|unique:clientes,cpf_cnpj,' . $cliente->id,
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('sucesso', 'Cliente apagado com sucesso!');
    }
}