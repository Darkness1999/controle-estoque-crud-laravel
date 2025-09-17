<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // KPI 1: Valor total do estoque (Custo e Venda)
        $valorEstoqueCusto = ProductVariation::sum(DB::raw('estoque_atual * preco_custo'));
        $valorEstoqueVenda = ProductVariation::sum(DB::raw('estoque_atual * preco_venda'));

        // KPI 2: Número de produtos com estoque baixo
        $variacoesEstoqueBaixo = ProductVariation::where('estoque_atual', '>', 0)
                                         ->whereColumn('estoque_atual', '<=', 'estoque_minimo')
                                         ->with('produto')
                                         ->get();
        $countEstoqueBaixo = $variacoesEstoqueBaixo->count();

        // KPI 3: Top 5 produtos mais vendidos (com mais saídas nos últimos 30 dias)
        $topProdutosVendidos = MovimentacaoEstoque::where('tipo', 'saida')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('product_variation_id', DB::raw('SUM(quantidade) as total_vendido'))
            ->groupBy('product_variation_id')
            ->orderBy('total_vendido', 'desc')
            ->with('productVariation.produto')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'valorEstoqueCusto',
            'valorEstoqueVenda',
            'countEstoqueBaixo',
            'variacoesEstoqueBaixo',
            'topProdutosVendidos'
        ));
    }
}