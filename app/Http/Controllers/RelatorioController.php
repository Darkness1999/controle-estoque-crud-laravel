<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoEstoque;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    /**
     * Mostra o relatório de movimentações de stock com filtros.
     */
    public function movimentacoes(Request $request)
    {
        // Começa a construir a consulta ao banco de dados
        $query = MovimentacaoEstoque::with('productVariation.produto', 'user')->latest();

        // Filtro por Variação de Produto (SKU)
        if ($request->filled('product_variation_id')) {
            $query->where('product_variation_id', $request->product_variation_id);
        }

        // Filtro por Tipo de Movimentação
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por Período (data de início e fim)
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Executa a consulta e pagina os resultados (15 por página)
        $movimentacoes = $query->paginate(15);

        // Busca todas as variações para popular o filtro de produtos
        $variations = ProductVariation::with('produto')->get();

        return view('relatorios.movimentacoes', compact('movimentacoes', 'variations'));
    }
}