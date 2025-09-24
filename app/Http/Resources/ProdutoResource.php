<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'codigo_barras' => $this->codigo_barras,
            'foto_url' => $this->foto_path ? asset('storage/' . $this->foto_path) : null,
            'categoria' => $this->categoria->nome,
            'marca' => $this->marca->nome,
            'estoque_total' => $this->total_stock,
            'variations' => ProductVariationResource::collection($this->whenLoaded('variations')),
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}