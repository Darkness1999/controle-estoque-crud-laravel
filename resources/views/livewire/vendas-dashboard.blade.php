<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar Produto (Nome ou SKU)</label>
                <div class="relative">
                    <input type="text" id="search" 
                           wire:model.live.debounce.300ms="searchTerm" 
                           placeholder="Digite pelo menos 2 caracteres..." 
                           class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                    <div wire:loading wire:target="searchTerm" class="absolute top-0 right-0 mt-2 mr-3">
                        <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="variation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selecione uma Variação</label>
                <select id="variation" wire:model.live="selectedVariationId" 
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">

                    <option value="">Selecione...</option>

                    @if(!empty($this->variations))
                        @foreach ($this->variations as $variation)
                            <option value="{{ $variation->id }}">
                                {{ $variation->produto?->nome }} - @foreach($variation->attributeValues as $value){{$value->valor}}@if(!$loop->last),@endif @endforeach (SKU: {{$variation->sku}})
                            </option>
                        @endforeach
                    @elseif(strlen($searchTerm) >= 2)
                        <option value="" disabled>Nenhum produto encontrado...</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Saídas nos Últimos 30 Dias</h3>
        <div class="text-center text-gray-500 py-4">(Selecione uma variação para ver o gráfico)</div>
    </div>
</div>