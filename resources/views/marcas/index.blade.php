<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('Gerenciamento de Marcas') }}
            </h2>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-md shadow-sm transition">
                Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">

                {{-- Cabeçalho da Tabela --}}
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        Marcas Cadastradas
                    </h3>
                    <a href="{{ route('marcas.create') }}"
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-wide transition">
                        Adicionar Marca
                    </a>
                </div>

                {{-- Alerta de Sucesso --}}
                @if(session('sucesso'))
                    <x-alert :message="session('sucesso')" />
                @endif

                {{-- Tabela --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($marcas as $marca)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $marca->nome }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-3">
                                            {{-- Botão Editar --}}
                                            <a href="{{ route('marcas.edit', $marca->id) }}"
                                               class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-md transition">
                                                Editar
                                            </a>

                                            {{-- Botão Apagar --}}
                                            <form method="POST" action="{{ route('marcas.destroy', $marca->id) }}"
                                                  onsubmit="return confirm('Tem certeza que deseja apagar esta marca?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition">
                                                    Apagar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                        Nenhuma marca cadastrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                <div class="mt-6">
                    {{ $marcas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
