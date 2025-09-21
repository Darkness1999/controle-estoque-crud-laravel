<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Movimentar Estoque') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ tipo: '{{ old('tipo', 'entrada') }}' }">
                    <h3 class="text-lg font-medium mb-4">Registrar Nova Movimentação</h3>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
                            <strong class="font-bold">Ocorreu um erro!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    <div class="mb-4">
                        <label for="barcode_search" class="block font-medium text-sm">Scanear Código de Barras (SKU)</label>
                        <input id="barcode_search" type="text" placeholder="Posicione o cursor aqui e scaneie o código" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 transition">
                        <div id="scan_success" class="mt-2 text-sm text-green-600 font-bold" style="display: none;">Produto Encontrado!</div>
                        <div id="scan_error" class="mt-2 text-sm text-red-600 font-bold" style="display: none;">Produto não encontrado!</div>
                    </div>

                    <form method="POST" action="{{ route('estoque.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="product_variation_id" class="block font-medium text-sm">Produto (SKU)</label>
                                <select id="product_variation_id" name="product_variation_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach ($variations as $variation)
                                        <option value="{{ $variation->id }}" data-sku="{{ $variation->sku }}" @if(old('product_variation_id') == $variation->id) selected @endif>
                                            {{ $variation->produto->nome }} - @foreach($variation->attributeValues as $value){{$value->valor}}@if(!$loop->last), @endif @endforeach (SKU: {{ $variation->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tipo" class="block font-medium text-sm">Tipo de Movimento</label>
                                <select id="tipo" name="tipo" x-model="tipo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                            </div>
                            <div>
                                <label for="quantidade" class="block font-medium text-sm">Quantidade</label>
                                <input id="quantidade" name="quantidade" type="number" min="1" value="{{ old('quantidade') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required>
                            </div>
                        </div>

                        <div x-show="tipo === 'entrada'" class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="fornecedor_id" class="block font-medium text-sm">Fornecedor</label>
                                    <select id="fornecedor_id" name="fornecedor_id" :disabled="tipo !== 'entrada'" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                        <option value="">Selecione um fornecedor</option>
                                        @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->id }}" @if(old('fornecedor_id') == $fornecedor->id) selected @endif>{{ $fornecedor->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="lote" class="block font-medium text-sm">Lote</label>
                                    <input id="lote" name="lote" type="text" value="{{ old('lote') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                </div>
                                <div>
                                    <label for="data_validade" class="block font-medium text-sm">Data de Validade</label>
                                    <input id="data_validade" name="data_validade" type="date" value="{{ old('data_validade') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                </div>
                            </div>
                        </div>

                        <div x-show="tipo === 'saida'" style="display: none;">
                            <label for="cliente_id" class="block font-medium text-sm">Cliente</label>
                            <select id="cliente_id" name="cliente_id" :disabled="tipo !== 'saida'" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                                <option value="">Selecione um cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @if(old('cliente_id') == $cliente->id) selected @endif>{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="motivo" class="block font-medium text-sm">Motivo (Opcional)</label>
                            <input id="motivo" name="motivo" type="text" value="{{ old('motivo') }}" placeholder="Ex: Compra NF 123, Venda balcão" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                        </div>
                         <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">Registrar Movimentação</button>
                        </div>
                    </form>
                </div>
            </div>

            </div>
    </div>

    @push('scripts')
        @endpush
</x-app-layout>