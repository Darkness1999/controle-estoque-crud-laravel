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
                    
                    @if ($errors->any() || session('sucesso'))
                        <div class="mb-4">
                            @if ($errors->any())
                                <div class="p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
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
                            
                        </div>
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
                                <select id="product_variation_id" name="product_variation_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500" required>
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
                                <select id="tipo" name="tipo" x-model="tipo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </select>
                            </div>
                            <div>
                                <label for="quantidade" class="block font-medium text-sm">Quantidade</label>
                                <input id="quantidade" name="quantidade" type="number" min="1" value="{{ old('quantidade') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500" required>
                            </div>
                        </div>

                        <div x-show="tipo === 'entrada'">
                            <label for="fornecedor_id" class="block font-medium text-sm">Fornecedor</label>
                            <select id="fornecedor_id" name="fornecedor_id" :disabled="tipo !== 'entrada'" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500">
                                <option value="">Selecione um fornecedor</option>
                                @foreach ($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}" @if(old('fornecedor_id') == $fornecedor->id) selected @endif>{{ $fornecedor->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="tipo === 'saida'" style="display: none;">
                            <label for="cliente_id" class="block font-medium text-sm">Cliente</label>
                            <select id="cliente_id" name="cliente_id" :disabled="tipo !== 'saida'" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500">
                                <option value="">Selecione um cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @if(old('cliente_id') == $cliente->id) selected @endif>{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="motivo" class="block font-medium text-sm">Motivo (Opcional)</label>
                            <input id="motivo" name="motivo" type="text" value="{{ old('motivo') }}" placeholder="Ex: Compra NF 123, Venda balcão" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500">
                        </div>
                         <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">Registrar Movimentação</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Últimas Movimentações</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="border-b dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Data</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Produto</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Tipo</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Qtd.</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Associado a</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Utilizador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($movimentacoes as $mov)
                                    <tr class="border-b @if($mov->tipo === 'entrada') bg-green-50 dark:bg-green-900/50 @else bg-red-50 dark:bg-red-900/50 @endif">
                                        <td class="px-4 py-2 text-sm">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <p class="font-semibold">{{ $mov->productVariation->produto->nome }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">SKU: {{ $mov->productVariation->sku }}</p>
                                        </td>
                                        <td class="px-4 py-2 text-sm font-semibold @if($mov->tipo === 'entrada') text-green-600 @else text-red-600 @endif">{{ ucfirst($mov->tipo) }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->quantidade }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->fornecedor->nome ?? $mov->cliente->nome ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $mov->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-2 text-center text-sm">Nenhuma movimentação registada ainda.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barcodeInput = document.getElementById('barcode_search');
            const variationSelect = document.getElementById('product_variation_id');
            const quantityInput = document.getElementById('quantidade');
            const successAlert = document.getElementById('scan_success');
            const errorAlert = document.getElementById('scan_error');

            let timeoutId;

            function showFeedback(type, message) {
                // Limpa qualquer timeout anterior
                clearTimeout(timeoutId);

                // Esconde ambas as mensagens
                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';
                
                if (type === 'success') {
                    successAlert.innerText = message;
                    successAlert.style.display = 'block';
                    barcodeInput.classList.remove('border-red-500');
                    barcodeInput.classList.add('border-green-500');
                } else {
                    errorAlert.innerText = message;
                    errorAlert.style.display = 'block';
                    barcodeInput.classList.remove('border-green-500');
                    barcodeInput.classList.add('border-red-500');
                }

                // Agenda para esconder a mensagem após 3 segundos
                timeoutId = setTimeout(() => {
                    successAlert.style.display = 'none';
                    errorAlert.style.display = 'none';
                    barcodeInput.classList.remove('border-green-500', 'border-red-500');
                }, 3000);
            }

            barcodeInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    const scannedSku = barcodeInput.value.trim();
                    if (!scannedSku) return;

                    let found = false;

                    for (let i = 0; i < variationSelect.options.length; i++) {
                        const option = variationSelect.options[i];
                        if (option.dataset.sku === scannedSku) {
                            variationSelect.value = option.value;
                            found = true;
                            break;
                        }
                    }

                    if (found) {
                        showFeedback('success', 'Produto Encontrado!');
                        barcodeInput.value = '';
                        quantityInput.focus();
                        quantityInput.select();
                    } else {
                        showFeedback('error', 'Produto não encontrado!');
                        variationSelect.value = ''; // Limpa a seleção anterior
                    }
                }
            });
            
            barcodeInput.addEventListener('input', function() {
                // Limpa feedback visual e alertas ao começar a digitar
                barcodeInput.classList.remove('border-green-500', 'border-red-500');
                successAlert.style.display = 'none';
                errorAlert.style.display = 'none';
                clearTimeout(timeoutId);
            });
        });
    </script>
    @endpush
</x-app-layout>