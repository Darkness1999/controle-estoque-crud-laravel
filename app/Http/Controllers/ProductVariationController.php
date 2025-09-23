<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductVariationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:product_variations,sku',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'estoque_inicial' => 'nullable|integer|min:0',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:valor_atributos,id',
        ]);

        // Cria a variação SEM o estoque
        $variation = $produto->variations()->create([
            'sku' => $validated['sku'],
            'preco_custo' => $validated['preco_custo'] ?? null,
            'preco_venda' => $validated['preco_venda'],
            'estoque_minimo' => $validated['estoque_minimo'] ?? 0,
        ]);

        // Anexa os valores de atributo
        $variation->attributeValues()->attach($validated['attribute_values']);

        // Se um estoque inicial foi fornecido, cria a movimentação de entrada e o lote
        if (!empty($validated['estoque_inicial']) && $validated['estoque_inicial'] > 0) {
            $lote = $variation->lotesEstoque()->create([
                'lote' => 'INICIAL',
                'quantidade_atual' => $validated['estoque_inicial'],
            ]);

            // LÓGICA CORRIGIDA: Agora passamos o fornecedor do produto pai
            $variation->movimentacoes()->create([
                'lote_estoque_id' => $lote->id,
                'user_id' => Auth::id(),
                'tipo' => 'entrada',
                'quantidade' => $validated['estoque_inicial'],
                'motivo' => 'Estoque Inicial',
                'fornecedor_id' => $produto->fornecedor_id, // <-- A CORREÇÃO MÁGICA
            ]);
        }

        return redirect()->route('produtos.edit', $produto->id)->with('sucesso', 'Variação adicionada com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariation $variation)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:product_variations,sku,' . $variation->id,
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
        ]);
        
        $variation->update($validated);

        return redirect()->route('produtos.edit', $variation->produto_id)->with('sucesso', 'Variação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariation $variation)
    {
        $produto_id = $variation->produto_id;
        $variation->delete();
        return redirect()->route('produtos.edit', $produto_id)->with('sucesso', 'Variação apagada com sucesso!');
    }
    
    /**
     * Mostra uma view otimizada para impressão da etiqueta de uma variação.
     */
    public function printLabel(ProductVariation $variation)
    {
        return view('variations.label', compact('variation'));
    }
}