<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard de Inteligência de Estoque') }}
            </h2>
            <form action="{{ route('dashboard') }}" method="GET">
                <select name="periodo" onchange="this.form.submit()" class="text-sm rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500">
                    <option value="30d" @if($periodo == '30d') selected @endif>Últimos 30 dias</option>
                    <option value="7d" @if($periodo == '7d') selected @endif>Últimos 7 dias</option>
                    <option value="este_mes" @if($periodo == 'este_mes') selected @endif>Este Mês</option>
                    <option value="hoje" @if($periodo == 'hoje') selected @endif>Hoje</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Saídas no Período</h3>
                    <div class="flex items-baseline space-x-2 mt-1">
                        <p class="text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalSaidasAtual }}</p>
                        @if (isset($tendenciaSaidas))
                            <span class="text-sm font-semibold @if($tendenciaSaidas['direcao'] == 'subiu') text-green-500 @else text-red-500 @endif">
                                @if($tendenciaSaidas['direcao'] == 'subiu') ▲ @else ▼ @endif
                                {{ number_format($tendenciaSaidas['percentagem'], 1, ',') }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">vs. período anterior</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor do Estoque (Venda)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($valorEstoqueVenda, 2, ',', '.') }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor do Estoque (Custo)</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">R$ {{ number_format($valorEstoqueCusto, 2, ',', '.') }}</p>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900/50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Alertas de Estoque Baixo</h3>
                    <p class="mt-1 text-3xl font-semibold text-yellow-900 dark:text-yellow-200">{{ $countEstoqueBaixo }} Variações</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
                <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Saídas no Período</h3>
                    <canvas id="saidasChart"></canvas>
                </div>
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Top Categorias por Valor em Estoque</h3>
                    <canvas id="categoriasChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Itens com Estoque Baixo</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-60 overflow-y-auto">
                        @forelse ($variacoesEstoqueBaixo as $variation)
                            <li class="py-3">
                                <a href="{{ route('produtos.edit', $variation->produto_id) }}" class="hover:underline">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $variation->produto->nome }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @foreach($variation->attributeValues as $value) {{ $value->valor }} @endforeach
                                        - <span class="font-bold text-red-500">Estoque: {{ $variation->estoque_atual }}</span>
                                        (Mín: {{ $variation->estoque_minimo }})
                                    </p>
                                </a>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">Nenhum item em alerta. ✅</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Produtos Parados no Período</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-60 overflow-y-auto">
                        @forelse ($produtosParados as $variation)
                            <li class="py-3">
                                <a href="{{ route('produtos.edit', $variation->produto_id) }}" class="hover:underline">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $variation->produto->nome }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @foreach($variation->attributeValues as $value) {{ $value->valor }} @endforeach
                                        - <span class="font-bold">Estoque: {{ $variation->estoque_atual }}</span>
                                    </p>
                                </a>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">Nenhum produto parado. 👍</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Últimas Movimentações</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-60 overflow-y-auto">
                        @forelse ($ultimasMovimentacoes as $mov)
                            <li class="py-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <span class="font-bold @if($mov->tipo === 'entrada') text-green-500 @else text-red-500 @endif">{{ $mov->quantidade }} un.</span>
                                    {{ $mov->tipo === 'entrada' ? 'de entrada em' : 'de saída em' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $mov->productVariation->produto->nome }} ({{ $mov->productVariation->sku }})
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $mov->created_at->diffForHumans() }} por {{ $mov->user->name }}
                                </p>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-center text-gray-500 dark:text-gray-400">Nenhuma movimentação recente.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Linha: Saídas no Período
        const saidasCtx = document.getElementById('saidasChart');
        new Chart(saidasCtx, {
            type: 'line',
            data: {
                labels: @json($labelsSaidas),
                datasets: [{
                    label: 'Quantidade de Saídas',
                    data: @json($dataSaidas),
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });

        // Gráfico de Rosca: Categorias por Valor
        const categoriasCtx = document.getElementById('categoriasChart');
        new Chart(categoriasCtx, {
            type: 'doughnut',
            data: {
                labels: @json($labelsCategorias),
                datasets: [{
                    label: 'Valor Total em Estoque',
                    data: @json($dataCategorias),
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.8)', 'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    hoverOffset: 4
                }]
            }
        });
    </script>
    @endpush
</x-app-layout>