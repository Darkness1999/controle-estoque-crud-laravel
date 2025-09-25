<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\MovimentacaoEstoque;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VendasDashboardController extends Controller
{
    // Apenas mostra a página principal do dashboard
    public function index()
    {
        return view('vendas.dashboard');
    }

    // Esta função é como uma mini-API para a nossa busca de produtos
    public function searchVariations(Request $request)
    {
        $searchTerm = $request->query('search', '');

        if (strlen($searchTerm) < 2) {
            return response()->json([]);
        }

        $variations = ProductVariation::with('produto', 'attributeValues.atributo')
            ->where(function ($query) use ($searchTerm) {
                $query->where('sku', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('produto', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('nome', 'like', '%' . $searchTerm . '%');
                    });
            })
            ->limit(20)
            ->get();

        return response()->json($variations);
    }

    // Esta função é como uma mini-API para os dados do gráfico
    public function getSalesData(ProductVariation $variation)
    {
        $vendas = MovimentacaoEstoque::where('product_variation_id', $variation->id)
            ->where('tipo', 'saida')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(fn($date) => Carbon::parse($date->created_at)->format('Y-m-d'))
            ->map(fn($group) => $group->sum('quantidade'));

        $labels = $vendas->keys()->map(fn ($date) => Carbon::parse($date)->format('d/m'))->toArray();
        $data = $vendas->values()->toArray();

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}