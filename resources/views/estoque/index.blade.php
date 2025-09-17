<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Movimentar Estoque') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Registrar Nova Movimentação</h3>
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('estoque.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="product_variation_id" class="block font-medium text-sm">Produto (SKU)</label>
                                <select id="product_variation_id" name="product_variation_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach ($variations as $variation)
                                        <option value="{{ $variation->id }}">
                                            {{ $variation->produto->nome }} - 
                                            @foreach($variation->attributeValues as $value)
                                                {{$value->valor}} 
                                            @endforeach
                                            (SKU: {{ $variation->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tipo" class="block font-medium text-sm">Tipo de Movimento</label>
                                <select id="tipo" name="tipo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                            </div>
                            <div>
                                <label for="quantidade" class="block font-medium text-sm">Quantidade</label>
                                <input id="quantidade" name="quantidade" type="number" min="1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                            </div>
                        </div>
                        <div>
                            <label for="motivo" class="block font-medium text-sm">Motivo (Opcional)</label>
                            <input id="motivo" name="motivo" type="text" placeholder="Ex: Compra do fornecedor X, Venda para cliente Y" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                        </div>
                        <div>
                            <label for="observacao" class="block font-medium text-sm">Observação (Opcional)</label>
                            <input id="observacao" name="observacao" type="text" placeholder="Ex: Produto em promoção" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                        </div>

                         <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
                                Registrar Movimentação
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Últimas 10 Movimentações</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <tbody>
                                @forelse ($movimentacoes as $mov)
                                    <tr class="border-b @if($mov->tipo === 'entrada') bg-green-50 dark:bg-green-900/50 @else bg-red-50 dark:bg-red-900/50 @endif">
                                        <td class="px-4 py-2">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2">{{ $mov->productVariation->produto->nome }} (...)</td>
                                        <td class="px-4 py-2">{{ $mov->productVariation->sku }}</td>
                                        <td class="px-4 py-2 font-semibold @if($mov->tipo === 'entrada') text-green-600 @else text-red-600 @endif">
                                            {{ ucfirst($mov->tipo) }}
                                        </td>
                                        <td class="px-4 py-2">{{ $mov->quantidade }}</td>
                                        <td class="px-4 py-2">{{ $mov->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-2 text-center">Nenhuma movimentação registrada ainda.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>