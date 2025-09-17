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
        $periodo = $request->input('periodo', '30d');
        $endDate = now();
        $startDate = $this->getStartDateFromPeriod($periodo);
        
        // Lógica para o Período Anterior
        $daysDifference = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($daysDifference + 1);
        $previousEndDate = $startDate->copy()->subDay();

        // --- 2. KPIs dos Cards ---
        $valorEstoqueCusto = ProductVariation::sum(DB::raw('estoque_atual * preco_custo'));
        $valorEstoqueVenda = ProductVariation::sum(DB::raw('estoque_atual * preco_venda'));
        
        $variacoesEstoqueBaixo = ProductVariation::where('estoque_atual', '>', 0)
            ->whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->with('produto')->get();
        $countEstoqueBaixo = $variacoesEstoqueBaixo->count();

        $totalSaidasAnterior = MovimentacaoEstoque::where('tipo', 'saida')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('quantidade');

        $totalSaidasAtual = MovimentacaoEstoque::where('tipo', 'saida')
            ->where('created_at', '>=', $startDate)
            ->sum('quantidade');

        $tendenciaSaidas = $this->calcularTendencia($totalSaidasAtual, $totalSaidasAnterior);
        
        // --- 3. Dados para Gráficos e Listas ---
        $saidasPorDia = MovimentacaoEstoque::where('tipo', 'saida')
            ->where('created_at', '>=', $startDate)
            ->groupBy('data')
            ->orderBy('data', 'asc')
            ->get([
                DB::raw('DATE(created_at) as data'),
                DB::raw('SUM(quantidade) as total')
            ]);
        
        $labelsSaidas = $saidasPorDia->pluck('data')->map(fn ($date) => Carbon::parse($date)->format('d/m'));
        $dataSaidas = $saidasPorDia->pluck('total');

        $categoriasPorValor = ProductVariation::join('produtos', 'product_variations.produto_id', '=', 'produtos.id')
            ->join('categorias', 'produtos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nome', DB::raw('SUM(product_variations.estoque_atual * product_variations.preco_venda) as valor_total'))
            ->groupBy('categorias.nome')
            ->orderBy('valor_total', 'desc')
            ->take(5)
            ->get();
        
        $labelsCategorias = $categoriasPorValor->pluck('nome');
        $dataCategorias = $categoriasPorValor->pluck('valor_total');
        
        $variacoesComSaida = MovimentacaoEstoque::where('tipo', 'saida')
            ->where('created_at', '>=', $startDate)
            ->pluck('product_variation_id')->unique();

        $produtosParados = ProductVariation::where('estoque_atual', '>', 0)
            ->whereNotIn('id', $variacoesComSaida)
            ->with('produto')
            ->take(10)
            ->get();
                            
        $ultimasMovimentacoes = MovimentacaoEstoque::with('productVariation.produto', 'user')->latest()->take(5)->get();

        // --- 4. Envio de TODAS as variáveis para a View ---
        return view('dashboard', compact(
            'valorEstoqueCusto', 'valorEstoqueVenda', 'countEstoqueBaixo', 'variacoesEstoqueBaixo',
            'labelsSaidas', 'dataSaidas',
            'labelsCategorias', 'dataCategorias',
            'produtosParados', 'ultimasMovimentacoes',
            'tendenciaSaidas', 'totalSaidasAtual', // <-- A VARIÁVEL ESTÁ AQUI AGORA
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