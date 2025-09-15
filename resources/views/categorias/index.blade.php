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

                    @foreach ($categorias as $categoria)
                        <div class="py-2">
                            <p><strong>Nome:</strong> {{ $categoria->nome }}</p>
                            <p><strong>Descrição:</strong> {{ $categoria->descricao }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-app-layout>