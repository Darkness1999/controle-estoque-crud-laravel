<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteApiController extends Controller
{
    public function index()
    {
        return ClienteResource::collection(Cliente::orderBy('nome')->paginate(15));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'nullable|string|max:18|unique:clientes',
            'email' => 'nullable|email|max:255|unique:clientes',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
        ]);

        $cliente = Cliente::create($validatedData);

        return (new ClienteResource($cliente))->response()->setStatusCode(201);
    }

    public function show(Cliente $cliente)
    {
        return new ClienteResource($cliente);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'cpf_cnpj' => ['sometimes', 'nullable', 'string', 'max:18', Rule::unique('clientes')->ignore($cliente->id)],
            'email' => ['sometimes', 'nullable', 'email', 'max:255', Rule::unique('clientes')->ignore($cliente->id)],
            'telefone' => 'sometimes|nullable|string|max:20',
            'endereco' => 'sometimes|nullable|string',
        ]);

        $cliente->update($validatedData);

        return new ClienteResource($cliente);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(null, 204);
    }
}