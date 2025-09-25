<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard de Vendas Interativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar Produto (Nome ou SKU)</label>
                        <input type="text" id="searchInput" placeholder="Digite pelo menos 2 caracteres..." 
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="variationSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selecione uma Variação</label>
                        <select id="variationSelect" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Aguardando busca...</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Saídas nos Últimos 30 Dias</h3>
                <div id="chartContainer">
                    <div id="chartAlert" class="p-4 text-center bg-yellow-100 dark:bg-yellow-900/50 border border-yellow-400 text-yellow-800 dark:text-yellow-300 rounded-md">
                        Selecione uma variação para ver o gráfico.
                    </div>
                    <canvas id="salesChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const variationSelect = document.getElementById('variationSelect');
            const chartContainer = document.getElementById('chartContainer');
            const chartAlert = document.getElementById('chartAlert');
            const canvas = document.getElementById('salesChart');
            let salesChart;
            let debounceTimer;

            // 1. Inicializa o gráfico (vazio)
            const ctx = canvas.getContext('2d');
            salesChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: [], datasets: [{
                    label: 'Quantidade Vendida',
                    data: [],
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                }]},
                options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
            });

            // 2. Event Listener para a BUSCA
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const searchTerm = searchInput.value;
                    variationSelect.innerHTML = '<option>A carregar...</option>';
                    if (searchTerm.length < 2) {
                        variationSelect.innerHTML = '<option>Aguardando busca...</option>';
                        return;
                    }

                    // Requisição AJAX (fetch) para a nossa rota de busca
                    fetch(`{{ route('vendas.search') }}?search=${searchTerm}`)
                        .then(response => response.json())
                        .then(data => {
                            variationSelect.innerHTML = ''; // Limpa as opções
                            if (data.length === 0) {
                                variationSelect.innerHTML = '<option>Nenhum resultado...</option>';
                            } else {
                                variationSelect.innerHTML = '<option value="">Selecione uma variação...</option>';
                                data.forEach(variation => {
                                    let attributes = variation.attribute_values.map(val => val.valor).join(', ');
                                    let option = new Option(`${variation.produto.nome} - ${attributes} (SKU: ${variation.sku})`, variation.id);
                                    variationSelect.add(option);
                                });
                            }
                        });
                }, 300);
            });

            // 3. Event Listener para a SELEÇÃO
            variationSelect.addEventListener('change', () => {
                const variationId = variationSelect.value;
                if (!variationId) {
                    canvas.style.display = 'none';
                    chartAlert.style.display = 'block';
                    chartAlert.innerText = 'Selecione uma variação para ver o gráfico.';
                    return;
                }

                chartAlert.style.display = 'block';
                chartAlert.innerText = 'A carregar dados do gráfico...';
                canvas.style.display = 'none';

                // Requisição AJAX (fetch) para a nossa rota de dados do gráfico
                fetch(`{{ url('/vendas/sales-data') }}/${variationId}`)
                    .then(response => response.json())
                    .then(chartData => {
                        if (chartData.data.length === 0) {
                            chartAlert.innerText = 'Nenhuma saída registada para este item no período.';
                        } else {
                            chartAlert.style.display = 'none';
                            canvas.style.display = 'block';
                            salesChart.data.labels = chartData.labels;
                            salesChart.data.datasets[0].data = chartData.data;
                            salesChart.update();
                        }
                    });
            });
        });
    </script>
    @endpush
</x-app-layout>