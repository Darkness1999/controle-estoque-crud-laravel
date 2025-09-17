<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatório de Movimentações de Stock') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Filtrar Relatório</h3>
                    <form method="GET" action="{{ route('relatorios.movimentacoes') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="product_variation_id" class="block text-sm font-medium">Produto (SKU)</label>
                                <select id="product_variation_id" name="product_variation_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                    <option value="">Todos os Produtos</option>
                                    @foreach ($variations as $variation)
                                        <option value="{{ $variation->id }}" {{ request('product_variation_id') == $variation->id ? 'selected' : '' }}>
                                            {{ $variation->produto->nome }} ({{ $variation->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tipo" class="block text-sm font-medium">Tipo</label>
                                <select id="tipo" name="tipo" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                    <option value="">Todos os Tipos</option>
                                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                                </select>
                            </div>
                            <div>
                                <label for="data_inicio" class="block text-sm font-medium">Data Início</label>
                                <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                            </div>
                            <div>
                                <label for="data_fim" class="block text-sm font-medium">Data Fim</label>
                                <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('relatorios.movimentacoes') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md text-sm">Limpar Filtros</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Aplicar Filtros</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="border-b dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm">Data</th>
                                    <th class="px-4 py-2 text-left text-sm">Produto</th>
                                    <th class="px-4 py-2 text-left text-sm">Tipo</th>
                                    <th class="px-4 py-2 text-left text-sm">Qtd.</th>
                                    <th class="px-4 py-2 text-left text-sm">Motivo</th>
                                    <th class="px-4 py-2 text-left text-sm">Utilizador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movimentacoes as $mov)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2 text-sm">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <p class="font-semibold">{{ $mov->productVariation->produto->nome }}</p>
                                            <p class="text-xs text-gray-600">SKU: {{ $mov->productVariation->sku }}</p>
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="font-semibold @if($mov->tipo === 'entrada') text-green-600 @else text-red-600 @endif">
                                                {{ ucfirst($mov->tipo) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm font-bold">{{ $mov->quantidade }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->motivo }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Nenhuma movimentação encontrada para os filtros selecionados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $movimentacoes->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>