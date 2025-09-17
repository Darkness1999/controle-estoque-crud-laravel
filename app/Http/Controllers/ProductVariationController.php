<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    public function store(Request $request, Produto $produto)
    {
        $request->validate([
            'sku' => 'required|string|unique:product_variations,sku',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:valor_atributos,id',
        ]);

        $variation = $produto->variations()->create($request->only([
        'sku', 'preco_venda', 'estoque_atual', 'preco_custo', 'estoque_minimo'
        ]));

        $variation->attributeValues()->attach($request->attribute_values);

        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Variação adicionada com sucesso!');
    }

    public function edit(ProductVariation $variation)
    {
        // Esta rota pode ser usada no futuro para uma página de edição dedicada se necessário,
        // mas por enquanto, nosso modal não precisa dela. Podemos deixá-la vazia ou retornar JSON.
        // Por simplicidade, vamos focar no update.
    }

    public function update(Request $request, ProductVariation $variation)
    {
        $request->validate([
            'sku' => 'required|string|unique:product_variations,sku,' . $variation->id,
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque_atual' => 'required|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
        ]);

        $variation->update($request->only(['sku', 'preco_custo', 'preco_venda', 'estoque_atual', 'estoque_minimo']));

        return redirect()->route('produtos.edit', $variation->produto_id)->with('sucesso', 'Variação atualizada com sucesso!');
    }

    public function destroy(ProductVariation $variation)
    {
        $produto_id = $variation->produto_id;
        $variation->delete();
        return redirect()->route('produtos.edit', $produto_id)->with('sucesso', 'Variação apagada com sucesso!');
    }
}