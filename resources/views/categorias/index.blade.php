<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Categorias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('categorias.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Adicionar Categoria
                        </a>
                    </div>

                    <p>Lista de Categorias:</p>

                    @if (session('sucesso'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-300 rounded" role="alert">
                            <p class="font-bold">Sucesso!</p>
                            <p>{{ session('sucesso') }}</p>
                        </div>
                    @endif

                    @foreach ($categorias as $categoria)
                        <div class="py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            {{-- Nome e Descrição --}}
                            <div>
                                <p class="font-semibold">{{ $categoria->nome }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $categoria->descricao }}</p>
                            </div>

                            {{-- Botões de Ação --}}
                            <div class="flex space-x-2">
                                {{-- Botão de Editar --}}
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md text-xs uppercase font-semibold hover:bg-yellow-600">
                                    Editar
                                </a>

                                {{-- FORMULÁRIO DE APAGAR --}}
                                <form method="POST" action="{{ route('categorias.destroy', $categoria->id) }}" onsubmit="return confirm('Você tem certeza que deseja apagar esta categoria?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-xs uppercase font-semibold hover:bg-red-700">
                                        Apagar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-app-layout>