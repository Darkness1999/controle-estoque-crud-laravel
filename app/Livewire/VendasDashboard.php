<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductVariation;
use Livewire\Attributes\Computed;

class VendasDashboard extends Component
{
    public string $searchTerm = '';
    public $selectedVariationId;

    #[Computed]
    public function variations()
    {
        if (strlen($this->searchTerm) < 2) {
            return [];
        }

        return ProductVariation::with('produto', 'attributeValues.atributo')
            ->where(function ($query) {
                $query->where('sku', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('produto', function ($subQuery) {
                        $subQuery->where('nome', 'like', '%' . $this->searchTerm . '%');
                    });
            })
            ->limit(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.vendas-dashboard');
    }
}