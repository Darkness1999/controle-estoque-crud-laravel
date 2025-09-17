<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor do Estoque (Custo)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($valorEstoqueCusto, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor do Estoque (Venda)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($valorEstoqueVenda, 2, ',', '.') }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 border-l-4 border-yellow-400 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Alertas de Estoque Baixo</h3>
                    <p class="mt-1 text-3xl font-semibold text-yellow-900 dark:text-yellow-200">{{ $countEstoqueBaixo }} Variações</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Top 5 Produtos Vendidos (Últimos 30 dias)</h3>
                    <canvas id="topProdutosChart"></canvas>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Itens com Estoque Baixo (< 5)</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($variacoesEstoqueBaixo as $variation)
                            <li class="py-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $variation->produto->nome }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @foreach($variation->attributeValues as $value) {{ $value->valor }} @endforeach
                                    - <span class="font-bold">Estoque: {{ $variation->estoque_atual }}</span>
                                </p>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">Nenhum item em alerta.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('topProdutosChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($topProdutosVendidos as $item)
                        '{{ $item->productVariation->produto->nome }} - {{ $item->productVariation->sku }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Quantidade Vendida',
                    data: [
                        @foreach($topProdutosVendidos as $item)
                            {{ $item->total_vendido }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
    @endpush
</x-app-layout>