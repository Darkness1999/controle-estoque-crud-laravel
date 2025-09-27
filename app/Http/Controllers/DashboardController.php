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
        $startDate = $this->getStartDateFromPeriod($periodo);
        $cacheKeyPeriodo = '_periodo_' . $periodo;

        // --- KPIs e Listas ---
        $valorEstoqueCusto = Cache::remember('dashboard_valor_estoque_custo', now()->addMinutes(10), function () {
            return ProductVariation::sum(DB::raw('preco_custo * (SELECT SUM(quantidade_atual) FROM lote_estoques WHERE product_variation_id = product_variations.id)'));
        });
        $valorEstoqueVenda = Cache::remember('dashboard_valor_estoque_venda', now()->addMinutes(10), function () {
            return ProductVariation::sum(DB::raw('preco_venda * (SELECT SUM(quantidade_atual) FROM lote_estoques WHERE product_variation_id = product_variations.id)'));
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

        $produtosParados = Cache::remember('dashboard_produtos_parados' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            $variacoesComSaida = MovimentacaoEstoque::where('tipo', 'saida')
                                ->where('created_at', '>=', $startDate)
                                ->pluck('product_variation_id')->unique();
            return ProductVariation::whereHas('lotesEstoque', fn($q) => $q->where('quantidade_atual', '>', 0))
                                ->whereNotIn('id', $variacoesComSaida)->with('produto')->take(10)->get();
        });
        $ultimasMovimentacoes = Cache::remember('dashboard_ultimas_movimentacoes', now()->addSeconds(30), function () {
            return MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->limit(10)->get();
        });

        // --- Dados para os Gráficos ---
        $categoriasPorValor = Cache::remember('dashboard_categorias_por_valor', now()->addMinutes(10), function () {
            return Categoria::query()
                ->join('produtos', 'categorias.id', '=', 'produtos.categoria_id')
                ->join('product_variations', 'produtos.id', '=', 'product_variations.produto_id')
                ->join('lote_estoques', 'product_variations.id', '=', 'lote_estoques.product_variation_id')
                ->select('categorias.id', 'categorias.nome', DB::raw('SUM(lote_estoques.quantidade_atual * product_variations.preco_venda) as valor_total'))
                ->groupBy('categorias.id', 'categorias.nome')
                ->orderBy('valor_total', 'desc')
                ->limit(5)
                ->get();
        });
        
        $saidasPorDia = Cache::remember('dashboard_saidas_por_dia' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'saida')->where('created_at', '>=', $startDate)->groupBy('data')->orderBy('data', 'asc')->get([ DB::raw('DATE(created_at) as data'), DB::raw('SUM(quantidade) as total') ]);
        });
        
        $entradasPorDia = Cache::remember('dashboard_entradas_por_dia' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'entrada')->where('created_at', '>=', $startDate)->groupBy('data')->orderBy('data', 'asc')->get([ DB::raw('DATE(created_at) as data'), DB::raw('SUM(quantidade) as total') ]);
        });

        // --- Alinhamento dos dados ---
        $labels = $saidasPorDia->pluck('data')->merge($entradasPorDia->pluck('data'))->unique()->sort();
        $dataSaidas = $labels->map(fn ($label) => $saidasPorDia->firstWhere('data', $label)->total ?? 0);
        $dataEntradas = $labels->map(fn ($label) => $entradasPorDia->firstWhere('data', $label)->total ?? 0);
        $labelsFormatados = $labels->map(fn ($date) => Carbon::parse($date)->format('d/m'));
        
        // --- Cálculo de Tendência ---
        $totalSaidasAtual = $dataSaidas->sum();
        $tendenciaSaidas = Cache::remember('dashboard_tendencia_saidas' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate, $totalSaidasAtual) {
            $diasNoPeriodo = $startDate->diffInDays(now()) + 1;
            if ($diasNoPeriodo <= 1) return null;

            $startDateAnterior = $startDate->copy()->subDays($diasNoPeriodo);
            $endDateAnterior = $startDate->copy()->subDay();
            
            $totalSaidasAnterior = MovimentacaoEstoque::where('tipo', 'saida')->whereBetween('created_at', [$startDateAnterior, $endDateAnterior])->sum('quantidade');

            return $this->calcularTendencia($totalSaidasAtual, $totalSaidasAnterior);
        });

        return view('dashboard', compact(
            'periodo', 'valorEstoqueCusto', 'valorEstoqueVenda', 'countEstoqueBaixo', 'variacoesEstoqueBaixo',
            'produtosParados', 'ultimasMovimentacoes', 'categoriasPorValor',
            'labelsFormatados', 'dataSaidas', 'dataEntradas',
            'totalSaidasAtual', 'tendenciaSaidas'
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