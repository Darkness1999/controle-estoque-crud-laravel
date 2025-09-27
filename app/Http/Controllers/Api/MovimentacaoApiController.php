<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EstoqueService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovimentacaoApiController extends Controller
{
    protected $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_variation_id' => 'required|exists:product_variations,id',
                'tipo' => 'required|in:saida', // API só aceita saídas por enquanto
                'quantidade' => 'required|integer|min:1',
                'motivo' => 'nullable|string|max:255',
                'cliente_id' => 'nullable|exists:clientes,id', // Opcional, mas validado se existir
            ]);
            
            // Criamos um array de "padrão" com todas as chaves que o serviço pode precisar
            $dadosDefault = [
                'cliente_id' => null,
                'fornecedor_id' => null,
                'lote' => null,
                'data_validade' => null,
            ];

            // Juntamos os dados validados com os padrões. 
            // Os valores validados irão sobrepor-se aos nulos.
            $dadosCompletos = array_merge($dadosDefault, $validatedData);
            
            // Agora enviamos um array sempre completo e previsível para o serviço
            $this->estoqueService->registrarMovimentacao($dadosCompletos);
            
            return response()->json(['message' => 'Movimentação registrada com sucesso.'], 201); // Created

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400); // Bad Request
        }
    }
}