<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $periodo = $request->input('periodo', '30d');
        $endDate = now();
        $startDate = $this->getStartDateFromPeriod($periodo);

        // --- Cache Keys Dinâmicas ---
        // Para dados que dependem do período, a chave do cache também deve depender
        $cacheKeyPeriodo = '_' . $periodo;

        // --- 2. KPIs dos Cards ---
        // Estes KPIs são sobre o estado atual, então não precisam depender do período
        $valorEstoqueCusto = Cache::remember('dashboard_valor_estoque_custo', now()->addMinutes(10), function () {
            return ProductVariation::sum(DB::raw('estoque_atual * preco_custo'));
        });

        $valorEstoqueVenda = Cache::remember('dashboard_valor_estoque_venda', now()->addMinutes(10), function () {
            return ProductVariation::sum(DB::raw('estoque_atual * preco_venda'));
        });

        $variacoesEstoqueBaixo = Cache::remember('dashboard_variacoes_estoque_baixo', now()->addMinutes(10), function () {
            return ProductVariation::where('estoque_atual', '>', 0)
                                    ->whereColumn('estoque_atual', '<=', 'estoque_minimo')
                                    ->with('produto')->get();
        });
        $countEstoqueBaixo = $variacoesEstoqueBaixo->count();

        // --- Cálculos de Tendência (agora com cache) ---
        $totalSaidasAtual = Cache::remember('dashboard_total_saidas_atual' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'saida')
                ->where('created_at', '>=', $startDate)
                ->sum('quantidade');
        });

        $tendenciaSaidas = Cache::remember('dashboard_tendencia_saidas' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate, $endDate, $totalSaidasAtual) {
            $daysDifference = $startDate->diffInDays($endDate);
            $previousStartDate = $startDate->copy()->subDays($daysDifference + 1);
            $previousEndDate = $startDate->copy()->subDay();
            $totalSaidasAnterior = MovimentacaoEstoque::where('tipo', 'saida')
                ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                ->sum('quantidade');
            return $this->calcularTendencia($totalSaidasAtual, $totalSaidasAnterior);
        });

        // --- 3. Dados para Gráficos e Listas (agora com cache) ---
        $saidasPorDia = Cache::remember('dashboard_saidas_por_dia' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            return MovimentacaoEstoque::where('tipo', 'saida')
                ->where('created_at', '>=', $startDate)
                ->groupBy('data')
                ->orderBy('data', 'asc')
                ->get([DB::raw('DATE(created_at) as data'), DB::raw('SUM(quantidade) as total')]);
        });

        $labelsSaidas = $saidasPorDia->pluck('data')->map(fn ($date) => Carbon::parse($date)->format('d/m'));
        $dataSaidas = $saidasPorDia->pluck('total');

        $categoriasPorValor = Cache::remember('dashboard_categorias_por_valor', now()->addMinutes(10), function () {
            return ProductVariation::join('produtos', 'product_variations.produto_id', '=', 'produtos.id')
                ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
                ->select('categorias.id', 'categorias.nome', DB::raw('SUM(product_variations.estoque_atual * product_variations.preco_venda) as valor_total'))
                ->groupBy('categorias.id', 'categorias.nome')
                ->orderBy('valor_total', 'desc')
                ->take(5)
                ->get();
        });

        $labelsCategorias = $categoriasPorValor->pluck('nome');
        $dataCategorias = $categoriasPorValor->pluck('valor_total');

        $produtosParados = Cache::remember('dashboard_produtos_parados' . $cacheKeyPeriodo, now()->addMinutes(10), function () use ($startDate) {
            $variacoesComSaida = MovimentacaoEstoque::where('tipo', 'saida')
                                ->where('created_at', '>=', $startDate)
                                ->pluck('product_variation_id')->unique();
            return ProductVariation::where('estoque_atual', '>', 0)
                                ->whereNotIn('id', $variacoesComSaida)
                                ->with('produto')
                                ->take(10)
                                ->get();
        });

        $ultimasMovimentacoes = Cache::remember('dashboard_ultimas_movimentacoes', now()->addSeconds(30), function () {
            return MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->take(5)->get();
        });

        // --- 4. Envio de TODAS as variáveis para a View ---
        return view('dashboard', compact(
            'valorEstoqueCusto', 'valorEstoqueVenda', 'countEstoqueBaixo', 'variacoesEstoqueBaixo',
            'labelsSaidas', 'dataSaidas',
            'categoriasPorValor',
            'produtosParados', 'ultimasMovimentacoes',
            'tendenciaSaidas', 'totalSaidasAtual',
            'periodo'
        ));
    }

    private function getStartDateFromPeriod(string $periodo): Carbon
    {
        return match ($periodo) {
            '7d' => now()->subDays(6)->startOfDay(),
            'este_mes' => now()->startOfMonth(),
            'hoje' => now()->startOfDay(),
            default => now()->subDays(29)->startOfDay(),
        };
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