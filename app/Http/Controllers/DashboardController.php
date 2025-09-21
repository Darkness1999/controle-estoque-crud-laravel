<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\MovimentacaoEstoque;
use App\Models\LoteEstoque;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $periodo = $request->input('periodo', '30d');
        $endDate = now();
        $startDate = $this->getStartDateFromPeriod($periodo);
        $cacheKeyPeriodo = '_' . $periodo;

        $valorEstoqueCusto = Cache::remember('dashboard_valor_estoque_custo', now()->addMinutes(10), function () {
            return LoteEstoque::join('product_variations', 'lote_estoques.product_variation_id', '=', 'product_variations.id')
                ->sum(DB::raw('lote_estoques.quantidade_atual * product_variations.preco_custo'));
        });

        $valorEstoqueVenda = Cache::remember('dashboard_valor_estoque_venda', now()->addMinutes(10), function () {
            return LoteEstoque::join('product_variations', 'lote_estoques.product_variation_id', '=', 'product_variations.id')
                ->sum(DB::raw('lote_estoques.quantidade_atual * product_variations.preco_venda'));
        });
        
        // --- KPI 2: Alertas de Estoque Baixo (LÓGICA REESCRITA E CORRIGIDA) ---
        $variacoesEstoqueBaixo = Cache::remember('dashboard_variacoes_estoque_baixo', now()->addMinutes(10), function () {
            // Subconsulta para calcular o stock total de cada variação
            $stockSubquery = LoteEstoque::select('product_variation_id', DB::raw('SUM(quantidade_atual) as total_stock'))
                                        ->groupBy('product_variation_id');

            // Junta a tabela de variações com os resultados da subconsulta
            return ProductVariation::joinSub($stockSubquery, 'stock_summary', function ($join) {
                    $join->on('product_variations.id', '=', 'stock_summary.product_variation_id');
                })
                ->where('stock_summary.total_stock', '>', 0)
                ->whereColumn('stock_summary.total_stock', '<=', 'product_variations.estoque_minimo')
                ->with('produto')
                ->get();
        });
        $countEstoqueBaixo = $variacoesEstoqueBaixo->count();

        // O restante do código permanece o mesmo
        $totalSaidasAtual = Cache::remember('dashboard_total_saidas_atual' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'saida')->where('created_at', '>=', $startDate)->sum('quantidade');
        });
        $tendenciaSaidas = Cache::remember('dashboard_tendencia_saidas' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate, $endDate, $totalSaidasAtual) {
            $daysDifference = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($daysDifference + 1);
            $previousEndDate = $startDate->copy()->subDay();
            $totalSaidasAnterior = MovimentacaoEstoque::where('tipo', 'saida')->whereBetween('created_at', [$previousStartDate, $previousEndDate])->sum('quantidade');
            return $this->calcularTendencia($totalSaidasAtual, $totalSaidasAnterior);
        });
        $saidasPorDia = Cache::remember('dashboard_saidas_por_dia' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'saida')->where('created_at', '>=', $startDate)->groupBy('data')->orderBy('data', 'asc')->get([DB::raw('DATE(created_at) as data'), DB::raw('SUM(quantidade) as total')]);
        });
        $labelsSaidas = $saidasPorDia->pluck('data')->map(fn ($date) => Carbon::parse($date)->format('d/m'));
        $dataSaidas = $saidasPorDia->pluck('total');
        $categoriasPorValor = Cache::remember('dashboard_categorias_por_valor', now()->addMinutes(10), function () {
            return LoteEstoque::join('product_variations', 'lote_estoques.product_variation_id', '=', 'product_variations.id')
                ->join('produtos', 'product_variations.produto_id', '=', 'produtos.id')
                ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
                ->select('categorias.id', 'categorias.nome', DB::raw('SUM(lote_estoques.quantidade_atual * product_variations.preco_venda) as valor_total'))
                ->groupBy('categorias.id', 'categorias.nome')->orderBy('valor_total', 'desc')->take(5)->get();
        });
        $variacoesComSaida = MovimentacaoEstoque::where('tipo', 'saida')->where('created_at', '>=', $startDate)->pluck('product_variation_id')->unique();
        $produtosParados = Cache::remember('dashboard_produtos_parados' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate, $variacoesComSaida) {
            return ProductVariation::whereHas('lotesEstoque', fn($q) => $q->where('quantidade_atual', '>', 0))
                                ->whereNotIn('id', $variacoesComSaida)->with('produto')->take(10)->get();
        });
        $ultimasMovimentacoes = Cache::remember('dashboard_ultimas_movimentacoes', now()->addSeconds(30), function () {
            return MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->take(5)->get();
        });

        return view('dashboard', compact(
            'valorEstoqueCusto', 'valorEstoqueVenda', 'countEstoqueBaixo', 'variacoesEstoqueBaixo',
            'labelsSaidas', 'dataSaidas', 'categoriasPorValor',
            'produtosParados', 'ultimasMovimentacoes',
            'tendenciaSaidas', 'totalSaidasAtual', 'periodo'
        ));
    }
    
    private function getStartDateFromPeriod(string $periodo): Carbon
    {
        switch ($periodo) {
            case '7d':
                return now()->subDays(6)->startOfDay();
            case 'este_mes':
                return now()->startOfMonth();
            case 'hoje':
                return now()->startOfDay();
            case '30d':
            default:
                return now()->subDays(29)->startOfDay();
        }
    }

    private function calcularTendencia($valorAtual, $valorAnterior): ?array
    {
        if ($valorAnterior == 0) {
            if ($valorAtual > 0) {
                return ['percentagem' => 100, 'direcao' => 'subiu'];
            }
            return null;
        }

        $diferenca = $valorAtual - $valorAnterior;
        $percentagem = ($diferenca / $valorAnterior) * 100;

        return [
            'percentagem' => abs($percentagem),
            'direcao' => $diferenca >= 0 ? 'subiu' : 'desceu',
        ];
    }
}