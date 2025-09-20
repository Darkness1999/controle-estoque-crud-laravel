<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerenciar Produto: <span class="text-indigo-500">{{ $produto->nome }}</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('sucesso'))
                <div class="p-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-300 rounded" role="alert">
                    {{ session('sucesso') }}
                </div>
            @endif
        </div>
    </div>

    <div class="pt-0 pb-12" x-data="{ activeTab: 'variacoes', isModalOpen: false, currentVariation: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="px-6 border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'variacoes'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'variacoes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'variacoes' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Variações e Estoque
                        </button>
                        <button @click="activeTab = 'gerais'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'gerais', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gerais' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Dados Gerais
                        </button>
                        <button @click="activeTab = 'atributos'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'atributos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'atributos' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Atributos do Produto
                        </button>
                    </nav>
                </div>
            </div>

            <div x-show="activeTab === 'variacoes'">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Variações de Produto (SKUs)</h3>
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Variações Cadastradas</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="border-b dark:border-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm">Atributos</th>
                                            <th class="px-4 py-2 text-left text-sm">SKU</th>
                                            <th class="px-4 py-2 text-left text-sm">Preço Venda</th>
                                            <th class="px-4 py-2 text-left text-sm">Estoque Atual</th>
                                            <th class="px-4 py-2 text-left text-sm">Estoque Mínimo</th>
                                            <th class="px-4 py-2 text-left text-sm">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($produto->variations as $variation)
                                            <tr class="border-b dark:border-gray-700">
                                                <td class="px-4 py-2 text-sm">
                                                    @foreach ($variation->attributeValues as $value)
                                                        <span class="font-medium">{{ $value->atributo->nome }}:</span> <span>{{ $value->valor }}</span>@if (!$loop->last), @endif
                                                    @endforeach
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ $variation->sku }}</td>
                                                <td class="px-4 py-2 text-sm">R$ {{ number_format($variation->preco_venda, 2, ',', '.') }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $variation->estoque_atual }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $variation->estoque_minimo }}</td>
                                                <td class="px-4 py-2 flex items-center space-x-4">
                                                    <button @click="isModalOpen = true; currentVariation = {{ $variation->toJson() }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-semibold">Editar</button>
                                                    <form method="POST" action="{{ route('variations.destroy', $variation->id) }}" onsubmit="return confirm('Tem certeza?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Apagar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="px-4 py-2 text-center text-sm">Nenhuma variação cadastrada.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-6 dark:border-gray-700">
                            <h4 class="font-semibold mb-4">Adicionar Nova Variação</h4>
                            @if($produto->attributes->isNotEmpty())
                                <form method="POST" action="{{ route('variations.store', $produto->id) }}" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($produto->attributes as $atributo)
                                            <div>
                                                <label for="attribute_{{ $atributo->id }}" class="block font-medium text-sm">{{ $atributo->nome }}</label>
                                                <select name="attribute_values[]" id="attribute_{{ $atributo->id }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                                    <option value="">Selecione...</option>
                                                    @foreach ($atributo->valorAtributos as $valor)
                                                        <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <div>
                                            <label for="sku" class="block font-medium text-sm">SKU</label>
                                            <input id="sku" name="sku" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                        </div>
                                        <div>
                                            <label for="preco_custo" class="block font-medium text-sm">Preço Custo</label>
                                            <input id="preco_custo" name="preco_custo" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                        </div>
                                        <div>
                                            <label for="preco_venda" class="block font-medium text-sm">Preço Venda</label>
                                            <input id="preco_venda" name="preco_venda" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                        </div>
                                        <div>
                                            <label for="estoque_atual" class="block font-medium text-sm">Estoque Inicial</label>
                                            <input id="estoque_atual" name="estoque_atual" type="number" value="0" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                        </div>
                                        <div>
                                            <label for="estoque_minimo" class="block font-medium text-sm">Estoque Mínimo</label>
                                            <input id="estoque_minimo" name="estoque_minimo" type="number" value="0" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-4">
                                        <button type="submit" class="px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
                                            Salvar Variação
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">Você precisa salvar pelo menos um atributo na aba "Atributos do Produto" para poder adicionar variações.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'gerais'" style="display: none;">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Dados Gerais do Produto</h3>
                        <form method="POST" action="{{ route('produtos.update', $produto->id) }}" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="foto" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Foto do Produto</label>
                                    @if ($produto->foto_path)
                                        <div class="mt-2"><img src="{{ asset('storage/' . $produto->foto_path) }}" alt="{{ $produto->nome }}" class="h-40 w-40 object-cover rounded-md"></div>
                                    @endif
                                    <input id="foto" name="foto" type="file" class="block mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700 file:text-indigo-700 dark:file:text-gray-300 hover:file:bg-indigo-100">
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="nome" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome do Produto</label>
                                        <input id="nome" name="nome" type="text" value="{{ old('nome', $produto->nome) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                    </div>
                                    <div>
                                        <label for="codigo_barras" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Código de Barras</label>
                                        <input id="codigo_barras" name="codigo_barras" type="text" value="{{ old('codigo_barras', $produto->codigo_barras) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="categoria_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Categoria</label>
                                    <select name="categoria_id" id="categoria_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" @if($categoria->id == old('categoria_id', $produto->categoria_id)) selected @endif>{{ $categoria->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="marca_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marca</label>
                                    <select name="marca_id" id="marca_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                        @foreach ($marcas as $marca)
                                            <option value="{{ $marca->id }}" @if($marca->id == old('marca_id', $produto->marca_id)) selected @endif>{{ $marca->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="fornecedor_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Fornecedor (Opcional)</label>
                                    <select name="fornecedor_id" id="fornecedor_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                        <option value="">Selecione</option>
                                        @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->id }}" @if($fornecedor->id == old('fornecedor_id', $produto->fornecedor_id)) selected @endif>{{ $fornecedor->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição</label>
                                <textarea id="descricao" name="descricao" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">{{ old('descricao', $produto->descricao) }}</textarea>
                            </div>

                            <div class="flex items-center justify-end mt-4 space-x-4">
                                <button type="submit" name="action" value="save_and_back" class="px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Salvar e Voltar
                                </button>
                                <button type="submit" name="action" value="save" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'atributos'" style="display: none;">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Atributos do Produto</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Selecione os atributos que este produto utiliza (ex: Cor, Tamanho). Isto irá determinar quais opções estarão disponíveis para criar variações.</p>
                        <form method="POST" action="{{ route('produtos.attributes.sync', $produto->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($todosAtributos as $atributo)
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="attributes[]" value="{{ $atributo->id }}" 
                                               @if($produto->attributes->contains($atributo)) checked @endif
                                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span>{{ $atributo->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">Salvar Atributos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="isModalOpen = false" style="display: none;">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.away="isModalOpen = false">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Editar Variação</h3>
                
                @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                @endif
                
                <form :action="`/variations/${currentVariation.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 text-gray-800 dark:text-gray-200">
                        <div>
                            <label for="edit_sku" class="block text-sm font-medium">SKU</label>
                            <input type="text" id="edit_sku" name="sku" x-model="currentVariation.sku" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label for="edit_preco_custo" class="block text-sm font-medium">Preço Custo</label>
                                <input type="text" id="edit_preco_custo" name="preco_custo" x-model="currentVariation.preco_custo" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                            <div>
                                <label for="edit_preco_venda" class="block text-sm font-medium">Preço Venda</label>
                                <input type="text" id="edit_preco_venda" name="preco_venda" x-model="currentVariation.preco_venda" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                            <div>
                                <label for="edit_estoque_atual" class="block text-sm font-medium">Estoque</bal>
                                <input type="number" id="edit_estoque_atual" name="estoque_atual" x-model="currentVariation.estoque_atual" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                            <div>
                                <label for="edit_estoque_minimo" class="block text-sm font-medium">Estoque Mínimo</label>
                                <input type="number" id="edit_estoque_minimo" name="estoque_minimo" x-model="currentVariation.estoque_minimo" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 mt-6">
                            <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md text-sm text-gray-800 dark:text-gray-200">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Salvar Alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>