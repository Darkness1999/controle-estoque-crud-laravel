<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editando Produto: <span class="text-indigo-500">{{ $produto->nome }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Dados Gerais do Produto</h3>
                    <form method="POST" action="{{ route('produtos.update', $produto->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">
                                Salvar Dados Gerais
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Variações de Produto (SKUs)</h3>

                    {{-- AQUI ENTRARÁ NOSSA TABELA DE VARIAÇÕES E O FORMULÁRIO PARA ADICIONAR NOVAS --}}
                    <p class="text-gray-500">Em breve: gerenciamento de variações (Cor, Tamanho, etc.), com controle de estoque e preço individual.</p>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>