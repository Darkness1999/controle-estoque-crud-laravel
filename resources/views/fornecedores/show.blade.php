<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalhes do Fornecedor: <span class="text-indigo-500">{{ $fornecedor->nome }}</span>
            </h2>
            <a href="{{ route('fornecedores.index') }}" class="text-sm px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md"> &larr; Voltar para a Lista</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2 dark:border-gray-700">Informações de Contacto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Nome</strong>
                            <span>{{ $fornecedor->nome }}</span>
                        </div>
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">CNPJ</strong>
                            <span>{{ $fornecedor->cnpj ?? 'N/A' }}</span>
                        </div>
                         <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Condições de Pagamento</strong>
                            <span>{{ $fornecedor->condicoes_pagamento ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2 dark:border-gray-700">Histórico de Entradas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="border-b dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm">Data</th>
                                    <th class="px-4 py-2 text-left text-sm">Produto</th>
                                    <th class="px-4 py-2 text-left text-sm">Quantidade</th>
                                    <th class="px-4 py-2 text-left text-sm">Motivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movimentacoes as $mov)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2 text-sm">{{ $mov->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <p class="font-semibold">{{ $mov->productVariation?->produto?->nome ?? 'Produto Removido' }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">SKU: {{ $mov->productVariation?->sku ?? 'N/A' }}</p>
                                        </td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->quantidade }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->motivo ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-2 text-center text-sm">Nenhuma entrada registada para este fornecedor.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $movimentacoes->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>