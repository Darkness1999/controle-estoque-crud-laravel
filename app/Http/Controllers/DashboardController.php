<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // --- 1. Lógica do Filtro de Período ---
        $periodo = $request->input('periodo', '30d'); // Padrão: 30 dias
        $startDate = now();

        switch ($periodo) {
            case '7d':
                $startDate = now()->subDays(7);
                break;
            case '30d':
                $startDate = now()->subDays(30);
                break;
            case 'este_mes':
                $startDate = now()->startOfMonth();
                break;
            case 'hoje':
                $startDate = now()->startOfDay();
                break;
        }

        // --- 2. KPIs dos Cards (não são afetados pelo período) ---
        $valorEstoqueCusto = ProductVariation::sum(DB::raw('estoque_atual * preco_custo'));
        $valorEstoqueVenda = ProductVariation::sum(DB::raw('estoque_atual * preco_venda'));
        $variacoesEstoqueBaixo = ProductVariation::where('estoque_atual', '>', 0)
                                                 ->whereColumn('estoque_atual', '<=', 'estoque_minimo')
                                                 ->with('produto')->get();
        $countEstoqueBaixo = $variacoesEstoqueBaixo->count();

        // --- 3. Gráfico de Linha: Saídas ao longo do tempo ---
        $saidasPorDia = MovimentacaoEstoque::where('tipo', 'saida')
            ->where('created_at', '>=', $startDate)
            ->groupBy('data')
            ->orderBy('data', 'asc')
            ->get([
                DB::raw('DATE(created_at) as data'),
                DB::raw('SUM(quantidade) as total')
            ]);

        // Formata para o gráfico
        $labelsSaidas = $saidasPorDia->pluck('data')->map(function ($date) {
            return Carbon::parse($date)->format('d/m');
        });
        $dataSaidas = $saidasPorDia->pluck('total');

        // --- 4. Gráfico de Rosca: Top Categorias por valor em estoque ---
        $categoriasPorValor = ProductVariation::join('produtos', 'product_variations.produto_id', '=', 'produtos.id')
            ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nome', DB::raw('SUM(product_variations.estoque_atual * product_variations.preco_venda) as valor_total'))
            ->groupBy('categorias.nome')
            ->orderBy('valor_total', 'desc')
            ->take(5)
            ->get();

        $labelsCategorias = $categoriasPorValor->pluck('nome');
        $dataCategorias = $categoriasPorValor->pluck('valor_total');

        // --- 5. Lista: Produtos Parados (sem saídas no período) ---
        $variacoesComSaida = MovimentacaoEstoque::where('tipo', 'saida')
                            ->where('created_at', '>=', $startDate)
                            ->pluck('product_variation_id')->unique();

        $produtosParados = ProductVariation::where('estoque_atual', '>', 0)
                            ->whereNotIn('id', $variacoesComSaida)
                            ->with('produto')
                            ->take(10)
                            ->get();

        // --- 6. Lista: Últimas movimentações ---
        $ultimasMovimentacoes = MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'valorEstoqueCusto', 'valorEstoqueVenda', 'countEstoqueBaixo', 'variacoesEstoqueBaixo',
            'labelsSaidas', 'dataSaidas',
            'labelsCategorias', 'dataCategorias',
            'produtosParados', 'ultimasMovimentacoes',
            'periodo' // Envia o período selecionado para a view
        ));
    }
}