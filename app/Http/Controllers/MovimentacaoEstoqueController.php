<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoEstoque;
use App\Models\ProductVariation;
use App\Models\Fornecedor;
use App\Models\Cliente;
use App\Models\LoteEstoque;
use App\Services\EstoqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class MovimentacaoEstoqueController extends Controller
{
     protected $estoqueService;

    // 2. Injetar o serviço no controller
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
            // 3. Usar o serviço para executar a lógica
            $this->estoqueService->registrarMovimentacao($validatedData);
        } catch (Exception $e) {
            return back()->withErrors(['operacao' => $e->getMessage()])->withInput();
        }

        return redirect()->route('estoque.movimentar')->with('sucesso', 'Movimentação de estoque registrada com sucesso!');
    }

    private function handleEntrada(Request $request, ProductVariation $variation)
    {
        // Cria um novo lote para esta entrada
        $lote = $variation->lotesEstoque()->create([
            'lote' => $request->lote,
            'data_validade' => $request->data_validade,
            'quantidade_atual' => $request->quantidade,
        ]);

        // Cria o registro da movimentação
        MovimentacaoEstoque::create([
            'product_variation_id' => $variation->id,
            'lote_estoque_id' => $lote->id,
            'user_id' => Auth::id(),
            'tipo' => 'entrada',
            'quantidade' => $request->quantidade,
            'motivo' => $request->motivo,
            'fornecedor_id' => $request->fornecedor_id,
        ]);
    }

    private function handleSaida(Request $request, ProductVariation $variation)
    {
        $quantidadeSaida = $request->quantidade;

        if ($quantidadeSaida > $variation->estoque_atual) {
            throw new Exception('A quantidade de saída não pode ser maior que o estoque atual.');
        }

        // Lógica FEFO: busca os lotes ordenados pela data de validade mais próxima
        $lotes = $variation->lotesEstoque()
                          ->where('quantidade_atual', '>', 0)
                          ->orderBy('data_validade', 'asc')
                          ->get();

        foreach ($lotes as $lote) {
            if ($quantidadeSaida <= 0) break;

            $removerDesteLote = min($quantidadeSaida, $lote->quantidade_atual);

            // Atualiza a quantidade do lote
            $lote->decrement('quantidade_atual', $removerDesteLote);

            // Cria um registro de movimentação para esta baixa de lote específico
            MovimentacaoEstoque::create([
                'product_variation_id' => $variation->id,
                'lote_estoque_id' => $lote->id,
                'user_id' => Auth::id(),
                'tipo' => 'saida',
                'quantidade' => $removerDesteLote,
                'motivo' => $request->motivo,
                'cliente_id' => $request->cliente_id,
            ]);

            $quantidadeSaida -= $removerDesteLote;
        }
    }
}