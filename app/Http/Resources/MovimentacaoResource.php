<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimentacaoResource extends JsonResource
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
            'tipo' => $this->tipo,
            'quantidade' => $this->quantidade,
            'motivo' => $this->motivo,
            'data_movimentacao' => $this->created_at->toDateTimeString(),
            'usuario' => $this->whenLoaded('user', fn() => $this->user->name),
            'variacao_produto' => $this->whenLoaded('productVariation', fn() => [
                'id' => $this->productVariation->id,
                'sku' => $this->productVariation->sku,
                'nome_produto' => $this->productVariation->produto->nome
            ])
        ];
    }
}
