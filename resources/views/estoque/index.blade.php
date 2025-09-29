<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('Movimentar Estoque') }}
            </h2>
            <a href="{{ route('produtos.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                Voltar para Produtos
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- Formulário --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                    Registrar Nova Movimentação
                </h3>

                {{-- ALERTAS --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-400 rounded-md text-red-700 dark:text-red-300">
                        <strong class="font-bold">⚠ Ocorreu um erro:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('sucesso'))
                    <x-alert :message="session('sucesso')" class="mb-4" />
                @endif

                {{-- SCAN CODE --}}
                <div class="mb-6">
                    <label for="barcode_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Scanear Código de Barras ou SKU
                    </label>
                    <input id="barcode_search" type="text"
                           placeholder="Posicione o cursor aqui e escaneie"
                           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                    <div id="scan_success" class="mt-2 text-sm text-green-600 font-semibold hidden">✅ Produto Encontrado!</div>
                    <div id="scan_error" class="mt-2 text-sm text-red-600 font-semibold hidden">❌ Produto não encontrado!</div>
                </div>

                {{-- FORM PRINCIPAL --}}
                <form method="POST" action="{{ route('estoque.store') }}" class="space-y-6" x-data="{ tipo: '{{ old('tipo','entrada') }}' }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="product_variation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Produto (SKU)</label>
                            <select id="product_variation_id" name="product_variation_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione um produto</option>
                                @foreach ($variations as $variation)
                                    <option value="{{ $variation->id }}"
                                            data-sku="{{ $variation->sku }}"
                                            data-barcode="{{ $variation->produto?->codigo_barras }}"
                                            @selected(old('product_variation_id') == $variation->id)>
                                        {{ $variation->produto?->nome ?? 'Produto Removido' }}
                                        - @foreach($variation->attributeValues as $value){{$value->valor}}@if(!$loop->last), @endif @endforeach
                                        (SKU: {{ $variation->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Movimento</label>
                            <select id="tipo" name="tipo" x-model="tipo" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </select>
                        </div>

                        <div>
                            <label for="quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade</label>
                            <input id="quantidade" name="quantidade" type="number" min="1" value="{{ old('quantidade') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- ENTRADA --}}
                    <div x-show="tipo === 'entrada'" class="border-t border-gray-200 dark:border-gray-700 pt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="fornecedor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fornecedor</label>
                            <select id="fornecedor_id" name="fornecedor_id" :disabled="tipo !== 'entrada'"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500">
                                <option value="">Selecione um fornecedor</option>
                                @foreach ($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}" @selected(old('fornecedor_id') == $fornecedor->id)>
                                        {{ $fornecedor->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="lote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lote</label>
                            <input id="lote" name="lote" type="text" value="{{ old('lote') }}" :disabled="tipo !== 'entrada'"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="data_validade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Validade</label>
                            <input id="data_validade" name="data_validade" type="date" value="{{ old('data_validade') }}" :disabled="tipo !== 'entrada'"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500">
                        </div>
                    </div>

                    {{-- SAÍDA --}}
                    <div x-show="tipo === 'saida'" class="border-t border-gray-200 dark:border-gray-700 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                            <select id="cliente_id" name="cliente_id" :disabled="tipo !== 'saida'"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500">
                                <option value="">Selecione um cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- MOTIVO --}}
                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo (Opcional)</label>
                        <input id="motivo" name="motivo" type="text" value="{{ old('motivo') }}"
                               placeholder="Ex: Compra NF 123, Venda balcão"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-md shadow-md transition">
                            Registrar Movimentação
                        </button>
                    </div>
                </form>
            </div>

            {{-- LISTAGEM --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Últimas Movimentações</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @foreach (['Data','Produto','Tipo','Qtd.','Associado a','Utilizador'] as $head)
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ $head }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($movimentacoes as $mov)
                                <tr class="@if($mov->tipo==='entrada') bg-green-50 dark:bg-green-900/40 @else bg-red-50 dark:bg-red-900/40 @endif">
                                    <td class="px-4 py-3 text-sm">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="font-medium">{{ $mov->productVariation?->produto?->nome ?? 'Produto Removido' }}</span>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">SKU: {{ $mov->productVariation?->sku ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold @if($mov->tipo==='entrada') text-green-700 @else text-red-700 @endif">
                                        {{ ucfirst($mov->tipo) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $mov->quantidade }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $mov->fornecedor->nome ?? $mov->cliente->nome ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $mov->user?->name ?? 'Utilizador Removido' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-600 dark:text-gray-300">
                                        Nenhuma movimentação registrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const barcodeInput = document.getElementById('barcode_search');
            const variationSelect = document.getElementById('product_variation_id');
            const quantityInput = document.getElementById('quantidade');
            const successAlert = document.getElementById('scan_success');
            const errorAlert = document.getElementById('scan_error');
            let timeoutId;

            function showFeedback(type, msg) {
                clearTimeout(timeoutId);
                successAlert.classList.add('hidden');
                errorAlert.classList.add('hidden');
                barcodeInput.classList.remove('border-green-500','border-red-500');

                if (type === 'success') {
                    successAlert.textContent = msg;
                    successAlert.classList.remove('hidden');
                    barcodeInput.classList.add('border-green-500');
                } else {
                    errorAlert.textContent = msg;
                    errorAlert.classList.remove('hidden');
                    barcodeInput.classList.add('border-red-500');
                }

                timeoutId = setTimeout(() => {
                    successAlert.classList.add('hidden');
                    errorAlert.classList.add('hidden');
                    barcodeInput.classList.remove('border-green-500','border-red-500');
                }, 3000);
            }

            barcodeInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const code = barcodeInput.value.trim();
                    if (!code) return;

                    let found = false;
                    for (const opt of variationSelect.options) {
                        if (opt.dataset.sku === code || opt.dataset.barcode === code) {
                            variationSelect.value = opt.value;
                            found = true;
                            break;
                        }
                    }

                    if (found) {
                        showFeedback('success', 'Produto Encontrado!');
                        barcodeInput.value = '';
                        quantityInput.focus();
                    } else {
                        showFeedback('error', 'Produto não encontrado!');
                        variationSelect.value = '';
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
