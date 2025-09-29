<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('📦 Gerenciamento de Produtos') }}
            </h2>

            <a href="{{ route('produtos.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 border border-transparent
                      rounded-lg text-sm font-semibold text-white shadow hover:bg-indigo-500
                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>
                Adicionar Produto
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtro ativo --}}
            @if ($categoriaFiltro)
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/40 border border-blue-300
                            text-blue-800 dark:text-blue-200 rounded-lg flex justify-between items-center">
                    <span>
                        Mostrando apenas a categoria:
                        <strong class="font-semibold">{{ $categoriaFiltro->nome }}</strong>
                    </span>
                    <a href="{{ route('produtos.index') }}"
                       class="text-blue-700 dark:text-blue-300 font-medium hover:underline">
                        Limpar filtro
                    </a>
                </div>
            @endif

            {{-- Mensagem de sucesso --}}
            @if(session('sucesso'))
                <div class="mb-6 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-200 p-4">
                    ✅ {{ session('sucesso') }}
                </div>
            @endif

            {{-- Lista de produtos --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-100">
                        Lista de Produtos Cadastrados
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @foreach (['Nome','Categoria','Marca','Estoque Total','Ações'] as $th)
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider
                                               text-gray-600 dark:text-gray-300">
                                        {{ $th }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($produtos as $produto)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $produto->nome }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $produto->categoria->nome }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $produto->marca->nome }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-gray-100">
                                        {{ $produto->total_stock }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex gap-2">

                                            {{-- Botão Detalhes --}}
                                            <a href="{{ route('produtos.show', $produto->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md bg-indigo-600 text-white
                                                    text-xs font-semibold hover:bg-indigo-500 transition">
                                                Detalhes
                                            </a>

                                            {{-- Botão Gerenciar --}}
                                            <a href="{{ route('produtos.edit', $produto->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md bg-yellow-500 text-white
                                                    text-xs font-semibold hover:bg-yellow-400 transition">
                                                Gerenciar
                                            </a>

                                            {{-- Botão Apagar --}}
                                            <form method="POST" action="{{ route('produtos.destroy', $produto->id) }}"
                                                onsubmit="return confirm('Tem certeza que deseja apagar este produto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 text-white
                                                            text-xs font-semibold hover:bg-red-500 transition">
                                                    Apagar
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-600 dark:text-gray-300">
                                        Nenhum produto cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginação (se usar) --}}
            <div class="mt-6">
                {{ $produtos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
