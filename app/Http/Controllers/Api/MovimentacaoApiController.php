<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EstoqueService;
use Illuminate\Http\Request;

class MovimentacaoApiController extends Controller
{
    protected $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_variation_id' => 'required|exists:product_variations,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'fornecedor_id' => 'nullable|required_if:tipo,entrada|exists:fornecedores,id',
            'cliente_id' => 'nullable|required_if:tipo,saida|exists:clientes,id',
            'lote' => 'nullable|required_if:tipo,entrada|string|max:255',
            'data_validade' => 'nullable|date',
        ]);

        try {
            $this->estoqueService->registrarMovimentacao($validatedData);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422); // Unprocessable Entity
        }

        return response()->json(['message' => 'Movimentação registrada com sucesso.'], 201); // Created
    }
}