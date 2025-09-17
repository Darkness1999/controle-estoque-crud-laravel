<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($categoriaFiltro)
                        <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900/50 border border-blue-400 text-blue-800 dark:text-blue-300 rounded">
                            Mostrando produtos apenas da categoria: <strong class="font-semibold">{{ $categoriaFiltro->nome }}</strong>.
                            <a href="{{ route('produtos.index') }}" class="ml-4 font-bold hover:underline">Limpar Filtro</a>
                        </div>
                    @endif

                    <div class="mb-4 flex justify-between items-center">
                        <p class="font-xl">Lista de Produtos Cadastrados</p>
                        <a href="{{ route('produtos.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Adicionar Produto
                        </a>
                    </div>

                    @if (session('sucesso'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-300 rounded" role="alert">
                            {{ session('sucesso') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="border-b-2 border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Marca</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estoque Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($produtos as $produto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $produto->nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $produto->categoria->nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $produto->marca->nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ $produto->total_stock }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('produtos.show', $produto->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">Detalhes</a>
                                        <a href="{{ route('produtos.edit', $produto->id) }}" class="text-yellow-600 hover:text-yellow-900">Gerenciar</a>
                                        <form method="POST" action="{{ route('produtos.destroy', $produto->id) }}" class="inline-block ml-4" onsubmit="return confirm('Tem certeza?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Nenhum produto cadastrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>