<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoEstoque;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimentacaoEstoqueController extends Controller
{
    /**
     * Mostra a página de movimentação de estoque.
     */
    public function index()
    {
        // Busca todas as variações para o dropdown
        $variations = ProductVariation::with('produto', 'attributeValues.atributo')->get();

        // Busca as últimas 10 movimentações para exibir no histórico
        $movimentacoes = MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->take(10)->get();

        return view('estoque.index', compact('variations', 'movimentacoes'));
    }

    /**
     * Salva uma nova movimentação de estoque.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_variation_id' => 'required|exists:product_variations,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'observacao' => 'nullable|string|max:1000',
        ]);

        // Busca a variação do produto
        $variation = ProductVariation::findOrFail($request->product_variation_id);

        // Validação extra: não permitir saída de estoque maior que o atual
        if ($request->tipo === 'saida' && $request->quantidade > $variation->estoque_atual) {
            return back()->withErrors(['quantidade' => 'A quantidade de saída não pode ser maior que o estoque atual.']);
        }

        // Usando uma Transaction para garantir a integridade dos dados
        DB::transaction(function () use ($request, $variation) {
            // 1. Cria o registro da movimentação
            MovimentacaoEstoque::create([
                'product_variation_id' => $request->product_variation_id,
                'user_id' => Auth::id(), // Pega o ID do usuário logado
                'tipo' => $request->tipo,
                'quantidade' => $request->quantidade,
                'motivo' => $request->motivo,
                'observacao' => $request->observacao,
            ]);

            // 2. Atualiza o estoque na tabela de variações
            if ($request->tipo === 'entrada') {
                $variation->increment('estoque_atual', $request->quantidade);
            } else {
                $variation->decrement('estoque_atual', $request->quantidade);
            }
        });

        return redirect()->route('estoque.movimentar')->with('sucesso', 'Movimentação de estoque registrada com sucesso!');
    }
}