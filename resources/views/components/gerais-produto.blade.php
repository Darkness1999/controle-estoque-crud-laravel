@props(['produto', 'categorias', 'marcas', 'fornecedores'])

<div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-6">Dados Gerais do Produto</h3>
        <form method="POST" action="{{ route('produtos.update', $produto->id) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label for="foto" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Foto do Produto</label>
                    @if ($produto->foto_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $produto->foto_path) }}" alt="{{ $produto->nome }}" class="h-40 w-40 object-cover rounded-md shadow">
                        </div>
                    @endif
                    <input id="foto" name="foto" type="file" class="block mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700 file:text-indigo-700 dark:file:text-gray-300 hover:file:bg-indigo-100">
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="nome" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome do Produto</label>
                        <input id="nome" name="nome" type="text" value="{{ old('nome', $produto->nome) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                    </div>
                    <div>
                        <label for="codigo_barras" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Código de Barras</label>
                        <input id="codigo_barras" name="codigo_barras" type="text" value="{{ old('codigo_barras', $produto->codigo_barras) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="categoria_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_id', $produto->categoria_id) == $categoria->id)>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="marca_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marca</label>
                    <select name="marca_id" id="marca_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->id }}" @selected(old('marca_id', $produto->marca_id) == $marca->id)>{{ $marca->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fornecedor_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Fornecedor (Opcional)</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="">Selecione</option>
                        @foreach ($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->id }}" @selected(old('fornecedor_id', $produto->fornecedor_id) == $fornecedor->id)>{{ $fornecedor->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">{{ old('descricao', $produto->descricao) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 mt-4 border-t dark:border-gray-700 pt-4">
                <button type="submit" name="action" value="save_and_back" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold text-sm transition">Salvar e Voltar</button>
                <button type="submit" name="action" value="save" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>