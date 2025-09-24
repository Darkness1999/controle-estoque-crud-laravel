<?php

namespace App\Services;

use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EstoqueService
{
    public function registrarMovimentacao(array $dados): void
    {
        $variation = ProductVariation::findOrFail($dados['product_variation_id']);
        $tipo = $dados['tipo'];
        $quantidade = $dados['quantidade'];

        if ($tipo === 'saida') {
            if ($quantidade > $variation->estoque_atual) {
                throw new Exception('A quantidade de saída não pode ser maior que o estoque atual.');
            }
            $this->handleSaida($dados, $variation, $quantidade);
        } else {
            $this->handleEntrada($dados, $variation, $quantidade);
        }
    }

    private function handleEntrada(array $dados, ProductVariation $variation, int $quantidade): void
    {
        $lote = $variation->lotesEstoque()->create([
            'lote' => $dados['lote'],
            'data_validade' => $dados['data_validade'],
            'quantidade_atual' => $quantidade,
        ]);

        $variation->movimentacoes()->create([
            'lote_estoque_id' => $lote->id,
            'user_id' => Auth::id(),
            'tipo' => 'entrada',
            'quantidade' => $quantidade,
            'motivo' => $dados['motivo'],
            'fornecedor_id' => $dados['fornecedor_id'],
        ]);
    }

    private function handleSaida(array $dados, ProductVariation $variation, int $quantidadeSaida): void
    {
        $lotes = $variation->lotesEstoque()
                          ->where('quantidade_atual', '>', 0)
                          ->orderBy('data_validade', 'asc')
                          ->get();

        foreach ($lotes as $lote) {
            if ($quantidadeSaida <= 0) break;

            $removerDesteLote = min($quantidadeSaida, $lote->quantidade_atual);
            $lote->decrement('quantidade_atual', $removerDesteLote);

            $variation->movimentacoes()->create([
                'lote_estoque_id' => $lote->id,
                'user_id' => Auth::id(),
                'tipo' => 'saida',
                'quantidade' => $removerDesteLote,
                'motivo' => $dados['motivo'],
                'cliente_id' => $dados['cliente_id'],
            ]);

            $quantidadeSaida -= $removerDesteLote;
        }
    }
}