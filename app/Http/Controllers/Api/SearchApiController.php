<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use App\Models\Produto;
use App\Http\Resources\ProductVariationResource;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    /**
     * Procura uma variação de produto pelo seu SKU ou pelo código de barras do produto principal.
     */
    public function searchByCode(string $code)
    {
        // 1. Tenta encontrar uma correspondência exata no SKU da variação
        $variation = ProductVariation::where('sku', $code)->first();

        // 2. Se não encontrar, tenta encontrar pelo código de barras do produto principal
        if (!$variation) {
            $produto = Produto::where('codigo_barras', $code)->first();

            // Se encontrar um produto, mas ele tiver mais de uma variação,
            // não podemos retornar um resultado específico, pois seria ambíguo.
            // Numa versão futura, poderíamos retornar todas as variações. Por agora, focamos na busca exata.
            if ($produto && $produto->variations()->count() === 1) {
                $variation = $produto->variations()->first();
            }
        }

        // 3. Se uma variação foi encontrada (seja por SKU ou código de barras)
        if ($variation) {
            // Carrega todos os relacionamentos para uma resposta completa
            $variation->load('produto.categoria', 'produto.marca', 'attributeValues.atributo', 'lotesEstoque');

            // Retorna a variação formatada pelo nosso Resource
            return new ProductVariationResource($variation);
        }

        // 4. Se não encontrar nada, retorna um erro 404
        return response()->json(['message' => 'Nenhum produto ou variação encontrado com este código.'], 404);
    }
}