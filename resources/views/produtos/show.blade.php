<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalhes do Produto: <span class="text-indigo-500">{{ $produto->nome }}</span>
            </h2>
            <a href="{{ route('produtos.index') }}" class="text-sm px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600"> &larr; Voltar para a Lista</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2 dark:border-gray-700">Informações Gerais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Nome</strong>
                            <span>{{ $produto->nome }}</span>
                        </div>
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Categoria</strong>
                            <span>{{ $produto->categoria->nome }}</span>
                        </div>
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Marca</strong>
                            <span>{{ $produto->marca->nome }}</span>
                        </div>
                        <div>
                            <strong class="block text-gray-500 dark:text-gray-400">Fornecedor</strong>
                            <span>{{ $produto->fornecedor->nome ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-1 md:col-span-3">
                            <strong class="block text-gray-500 dark:text-gray-400">Descrição</strong>
                            <p class="mt-1">{{ $produto->descricao ?? 'Nenhuma descrição fornecida.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2 dark:border-gray-700">Variações (SKUs)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="border-b dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm">Atributos</th>
                                    <th class="px-4 py-2 text-left text-sm">SKU</th>
                                    <th class="px-4 py-2 text-left text-sm">Preço Custo</th>
                                    <th class="px-4 py-2 text-left text-sm">Preço Venda</th>
                                    <th class="px-4 py-2 text-left text-sm">Estoque</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produto->variations as $variation)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2">
                                            @foreach ($variation->attributeValues as $value)
                                                <span class="font-medium">{{ $value->atributo->nome }}:</span> <span>{{ $value->valor }}</span>@if (!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-2">{{ $variation->sku }}</td>
                                        <td class="px-4 py-2">R$ {{ number_format($variation->preco_custo, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2">R$ {{ number_format($variation->preco_venda, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 font-bold">{{ $variation->estoque_atual }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-2 text-center text-sm">Nenhuma variação cadastrada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>