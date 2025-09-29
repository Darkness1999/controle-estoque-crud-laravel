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

                    {{-- Exibição de erros --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-800 dark:text-red-300 rounded-md">
                            <strong class="font-bold">Ocorreu um erro:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulário --}}
                    <form method="POST" action="{{ route('produtos.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        {{-- Dados básicos --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Produto</label>
                                    <input id="nome" name="nome" type="text" required
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>
                                <div>
                                    <label for="codigo_barras" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Barras</label>
                                    <input id="codigo_barras" name="codigo_barras" type="text"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>
                            </div>

                            {{-- Upload de imagem --}}
                            <div>
                                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto do Produto</label>
                                <input id="foto" name="foto" type="file"
                                    class="mt-2 block w-full text-sm text-gray-600 dark:text-gray-300
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700
                                           file:text-indigo-700 dark:file:text-gray-300
                                           hover:file:bg-indigo-100 dark:hover:file:bg-gray-600" />
                            </div>
                        </div>

                        {{-- Seleções de relacionamento --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t dark:border-gray-700">
                            <div>
                                <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                <select name="categoria_id" id="categoria_id" required
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecione</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="marca_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marca</label>
                                <select name="marca_id" id="marca_id" required
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecione</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="fornecedor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fornecedor (Opcional)</label>
                                <select name="fornecedor_id" id="fornecedor_id"
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Selecione</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                       focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Botões de ação --}}
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                            {{-- Botão cancelar/voltar --}}
                            <a href="{{ route('produtos.index') }}"
                               class="inline-flex items-center px-5 py-2.5 bg-gray-200 dark:bg-gray-700
                                      text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-md shadow-sm
                                      transition hover:bg-gray-300 dark:hover:bg-gray-600
                                      focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500
                                      focus:ring-offset-1 dark:focus:ring-offset-gray-800">
                                ← Cancelar
                            </a>

                            {{-- Botão principal --}}
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold
                                       rounded-md shadow-sm transition
                                       hover:bg-indigo-500 focus:outline-none focus:ring-2
                                       focus:ring-indigo-400 dark:focus:ring-indigo-500
                                       focus:ring-offset-1 dark:focus:ring-offset-gray-800">
                                Salvar e Avançar para Variações
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
