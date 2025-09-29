<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Valores para o Atributo:
            <span class="text-indigo-500">{{ $atributo->nome }}</span>
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Cabeçalho e Botões --}}
                    <div class="mb-6 flex justify-between items-center">
                        <a href="{{ route('atributos.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                            Voltar para Atributos
                        </a>
                        <a href="{{ route('valores.create', $atributo->id) }}"
                           class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            + Adicionar Valor
                        </a>
                    </div>

                    {{-- Mensagem de sucesso --}}
                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    {{-- Tabela de Valores --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Valor (Ex: Azul, P, 110v)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($valores as $valor)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-sm font-medium">{{ $valor->valor }}</td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('valores.destroy', $valor->id) }}"
                                                  onsubmit="return confirm('Tem certeza que deseja apagar este valor?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-md text-xs font-semibold hover:bg-red-700 transition">
                                                    Apagar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhum valor cadastrado para este atributo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    <div class="mt-6">
                        {{ $valores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
