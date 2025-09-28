<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'preco_venda' => $this->preco_venda,
            'estoque_atual' => $this->estoque_atual,
            'atributos' => $this->attributeValues->map(function ($value) {
                return $value->atributo->nome . ': ' . $value->valor;
            })->implode(', '),
            // Apenas inclui os lotes se eles já estiverem carregados pelo controller
            'lotes_estoque' => $this->whenLoaded('lotesEstoque', function() {
                return $this->lotesEstoque->map(function ($lote) {
                    return [
                        'lote' => $lote->lote,
                        'quantidade' => $lote->quantidade_atual,
                        'data_validade' => $lote->data_validade,
                    ];
                });
            }),
        ];
    }
}