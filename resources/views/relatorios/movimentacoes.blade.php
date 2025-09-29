<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('Relatório de Movimentações de Stock') }}
            </h2>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- FILTROS --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">                   
                    Filtrar Relatório
                </h3>
                <form method="GET" action="{{ route('relatorios.movimentacoes') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {{-- Produto --}}
                        <div>
                            <label for="product_variation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Produto (SKU)
                            </label>
                            <select id="product_variation_id" name="product_variation_id"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos os Produtos</option>
                                @foreach ($variations as $variation)
                                    <option value="{{ $variation->id }}" {{ request('product_variation_id') == $variation->id ? 'selected' : '' }}>
                                        {{ $variation->produto?->nome }} ({{ $variation->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo
                            </label>
                            <select id="tipo" name="tipo"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todos os Tipos</option>
                                <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                            </select>
                        </div>

                        {{-- Data Início --}}
                        <div>
                            <label for="data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Data Início
                            </label>
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}"
                                   class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Data Fim --}}
                        <div>
                            <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Data Fim
                            </label>
                            <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}"
                                   class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('relatorios.movimentacoes') }}"
                           class="px-5 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-md shadow-sm transition">
                            Limpar Filtros
                        </a>
                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABELA DE RESULTADOS --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produto</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Motivo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Utilizador</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($movimentacoes as $mov)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $mov->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <p class="font-semibold">{{ $mov->productVariation?->produto?->nome ?? 'Produto Removido' }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            SKU: {{ $mov->productVariation?->sku ?? 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-semibold @if($mov->tipo === 'entrada') text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                                            {{ ucfirst($mov->tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $mov->quantidade }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $mov->motivo ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $mov->user?->name ?? 'Utilizador Removido' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-300">
                                        Nenhuma movimentação encontrada para os filtros selecionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO --}}
                <div class="mt-6">
                    {{ $movimentacoes->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
