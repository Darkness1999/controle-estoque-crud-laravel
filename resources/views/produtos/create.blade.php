<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Novo Produto (Passo 1 de 2)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
                            <strong class="font-bold">Ocorreu um erro!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('produtos.store') }}" class="space-y-4" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="nome" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome do Produto</label>
                                    <input id="nome" name="nome" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                </div>
                                <div>
                                    <label for="codigo_barras" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Código de Barras</label>
                                    <input id="codigo_barras" name="codigo_barras" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                            </div>
                            <div>
                                <label for="foto" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Foto do Produto</label>
                                <input id="foto" name="foto" type="file" class="block mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700 file:text-indigo-700 dark:file:text-gray-300 hover:file:bg-indigo-100">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t dark:border-gray-700">
                            <div>
                                <label for="categoria_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Categoria</label>
                                <select name="categoria_id" id="categoria_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                    <option value="">Selecione</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="marca_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marca</label>
                                <select name="marca_id" id="marca_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                    <option value="">Selecione</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="fornecedor_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Fornecedor (Opcional)</label>
                                <select name="fornecedor_id" id="fornecedor_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <option value="">Selecione</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Salvar e Avançar para Variações
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>