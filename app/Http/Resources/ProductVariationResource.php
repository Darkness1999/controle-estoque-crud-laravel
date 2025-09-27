<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
        ];
    }
}