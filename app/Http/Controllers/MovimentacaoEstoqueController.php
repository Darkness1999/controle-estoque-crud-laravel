<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\Fornecedor;
use App\Models\Cliente;
use App\Models\MovimentacaoEstoque;
use App\Services\EstoqueService;
use Illuminate\Http\Request;

class MovimentacaoEstoqueController extends Controller
{
    protected $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }

    public function index()
    {
        $variations = ProductVariation::with('produto', 'attributeValues.atributo')->get();
        $movimentacoes = MovimentacaoEstoque::with('productVariation.produto', 'user', 'fornecedor', 'cliente')->latest()->take(10)->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();
        return view('estoque.index', compact('variations', 'movimentacoes', 'fornecedores', 'clientes'));
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
            return back()->withErrors(['operacao' => $e->getMessage()])->withInput();
        }

        return redirect()->route('estoque.movimentar')->with('sucesso', 'Movimentação de estoque registrada com sucesso!');
    }
}