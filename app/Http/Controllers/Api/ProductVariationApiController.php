<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\ProductVariation;
use App\Http\Resources\ProductVariationResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariationApiController extends Controller
{
    /**
     * Cria uma nova variação para um produto existente.
     */
    public function store(Request $request, Produto $produto)
    {
        $validatedData = $request->validate([
            'sku' => 'required|string|max:255|unique:product_variations',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:valor_atributos,id',
        ]);

        // Cria a variação associada ao produto pai
        $variation = $produto->variations()->create($validatedData);

        // Anexa os atributos à variação na tabela-pivô
        $variation->attributeValues()->attach($validatedData['attribute_values']);

        // Retorna a nova variação formatada, com status 201 (Created)
        return (new ProductVariationResource($variation))->response()->setStatusCode(201);
    }

    /**
     * Lista todas as variações de um produto específico.
     */
    public function index(Produto $produto)
    {
        return ProductVariationResource::collection($produto->variations);
    }

    /**
     * Atualiza uma variação existente.
     */
    public function update(Request $request, ProductVariation $variation)
    {
        $validatedData = $request->validate([
            'sku' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('product_variations')->ignore($variation->id)],
            'preco_custo' => 'sometimes|nullable|numeric|min:0',
            'preco_venda' => 'sometimes|required|numeric|min:0',
            'estoque_minimo' => 'sometimes|nullable|integer|min:0',
        ]);

        $variation->update($validatedData);

        return new ProductVariationResource($variation);
    }

    /**
     * Apaga (soft delete) uma variação.
     */
    public function destroy(ProductVariation $variation)
    {
        $variation->delete(); // Executa o Soft Delete

        return response()->json(null, 204); // Resposta "No Content" para sucesso
    }
}