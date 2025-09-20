<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Adicionar Valor para: <span class="text-indigo-500">{{ $atributo->nome }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('valores.index', $atributo->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900"> &larr; Voltar para a Lista de Valores</a>
                    </div>

                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif
                    {{-- Formulário de Criação --}}
                    <form method="POST" action="{{ route('valores.store', $atributo->id) }}">
                        @csrf
                        <div>
                            <label for="valor" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome do Valor (Ex: Azul, M, 220v)</label>
                            <input id="valor" name="valor" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500" required autofocus>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase">Salvar Valor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>