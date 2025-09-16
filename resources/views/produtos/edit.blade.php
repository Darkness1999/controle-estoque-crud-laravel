<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Produto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('produtos.update', $produto->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="nome" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome do Produto</label>
                                <input id="nome" name="nome" type="text" value="{{ old('nome', $produto->nome) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                            </div>
                            <div>
                                <label for="codigo_interno" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Código Interno</label>
                                <input id="codigo_interno" name="codigo_interno" type="text" value="{{ old('codigo_interno', $produto->codigo_interno) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                            <div>
                                <label for="sku" class="block font-medium text-sm text-gray-700 dark:text-gray-300">SKU</label>
                                <input id="sku" name="sku" type="text" value="{{ old('sku', $produto->sku) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="categoria_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Categoria</label>
                                <select name="categoria_id" id="categoria_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" @if($categoria->id == old('categoria_id', $produto->categoria_id)) selected @endif>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="fornecedor_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Fornecedor (Opcional)</label>
                                <select name="fornecedor_id" id="fornecedor_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Selecione um Fornecedor</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->id }}" @if($fornecedor->id == old('fornecedor_id', $produto->fornecedor_id)) selected @endif>
                                            {{ $fornecedor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="estoque_atual" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Estoque Atual</label>
                                <input id="estoque_atual" name="estoque_atual" type="number" value="{{ old('estoque_atual', $produto->estoque_atual) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                            <div>
                                <label for="estoque_minimo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Estoque Mínimo</label>
                                <input id="estoque_minimo" name="estoque_minimo" type="number" value="{{ old('estoque_minimo', $produto->estoque_minimo) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="preco_custo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Preço de Custo (R$)</label>
                                <input id="preco_custo" name="preco_custo" type="text" value="{{ old('preco_custo', $produto->preco_custo) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                            <div>
                                <label for="preco_venda" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Preço de Venda (R$)</label>
                                <input id="preco_venda" name="preco_venda" type="text" value="{{ old('preco_venda', $produto->preco_venda) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                            </div>
                        </div>

                        <div>
                            <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('descricao', $produto->descricao) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Atualizar Produto
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>