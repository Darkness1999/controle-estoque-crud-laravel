<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">
                📊 Dashboard de Inteligência de Estoque
            </h2>
            <form action="{{ route('dashboard') }}" method="GET">
                <select name="periodo" onchange="this.form.submit()"
                    class="text-sm rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="30d" @selected($periodo=='30d')>Últimos 30 dias</option>
                    <option value="7d"  @selected($periodo=='7d')>Últimos 7 dias</option>
                    <option value="este_mes" @selected($periodo=='este_mes')>Este Mês</option>
                    <option value="hoje" @selected($periodo=='hoje')>Hoje</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">

        {{-- ==== MÉTRICAS PRINCIPAIS ==== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard-card 
                title="Total de Saídas no Período"
                :value="$totalSaidasAtual"
                :trend="$tendenciaSaidas"
                icon="⬆️" />

            <x-dashboard-card 
                title="Valor do Estoque (Venda)"
                value="R$ {{ number_format($valorEstoqueVenda, 2, ',', '.') }}"
                color="indigo" />

            <x-dashboard-card 
                title="Valor do Estoque (Custo)"
                value="R$ {{ number_format($valorEstoqueCusto, 2, ',', '.') }}"
                color="blue" />

            <x-dashboard-card 
                title="Alertas de Estoque Baixo"
                value="{{ $countEstoqueBaixo }} Variações"
                color="yellow"
                warning />
        </div>

        {{-- ==== GRÁFICOS PRINCIPAIS ==== --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">📈 Saídas no Período</h3>
                <canvas id="saidasChart"></canvas>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">🥇 Top Categorias por Valor</h3>
                <canvas id="categoriasChart"></canvas>
            </div>
        </div>

        {{-- ==== NOVO GRÁFICO COMPARATIVO ==== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">📊 Entradas vs Saídas</h3>
            <canvas id="comparativoChart"></canvas>
        </div>

        {{-- ==== LISTAGENS ==== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <x-dashboard-list title="⚠️ Itens com Estoque Baixo" :items="$variacoesEstoqueBaixo" type="baixo" />
            <x-dashboard-list title="⏳ Produtos Parados" :items="$produtosParados" />
            <x-dashboard-list title="📜 Últimas Movimentações" :items="$ultimasMovimentacoes" type="mov" />
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const gradient = ctx => {
            const g = ctx.createLinearGradient(0, 0, 0, 400);
            g.addColorStop(0, 'rgba(79,70,229,0.4)');
            g.addColorStop(1, 'rgba(79,70,229,0)');
            return g;
        };

        // === Dados ===
        const categoriasData  = @json($categoriasPorValor);
        const categoriaLabels = categoriasData.map(i => i.nome);
        const categoriaValores= categoriasData.map(i => i.valor_total);

        // Saídas no período (linha)
        new Chart(document.getElementById('saidasChart'), {
            type: 'line',
            data: {
                labels: @json($labelsFormatados),
                datasets: [{
                    label: 'Saídas',
                    data: @json($dataSaidas),
                    fill: true,
                    backgroundColor: ctx => gradient(ctx.chart.ctx),
                    borderColor: 'rgba(79,70,229,1)',
                    tension: 0.4,
                    pointBackgroundColor: 'white',
                    pointBorderColor: 'rgba(79,70,229,1)',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Categorias (rosca)
        new Chart(document.getElementById('categoriasChart'), {
            type: 'doughnut',
            data: {
                labels: categoriaLabels,
                datasets: [{
                    data: categoriaValores,
                    backgroundColor: [
                        '#4f46e5','#60a5fa','#facc15','#34d399','#f472b6'
                    ]
                }]
            },
            options: {
                onClick(evt, els){
                    if(els.length>0){
                        const idx = els[0].index;
                        const id = categoriasData[idx]?.id;
                        if(id) window.location.href = `{{ route('produtos.index') }}?categoria_id=${id}`;
                    }
                },
                plugins:{ legend:{ position:'bottom' } }
            }
        });

        // Comparativo Entradas vs Saídas
        new Chart(document.getElementById('comparativoChart'), {
            type: 'bar',
            data: {
                labels: @json($labelsFormatados),
                datasets: [
                    {
                        label: 'Entradas',
                        data: @json($dataEntradas ?? []),
                        backgroundColor: 'rgba(34,197,94,0.7)'
                    },
                    {
                        label: 'Saídas',
                        data: @json($dataSaidas),
                        backgroundColor: 'rgba(239,68,68,0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position:'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endpush
</x-app-layout>
