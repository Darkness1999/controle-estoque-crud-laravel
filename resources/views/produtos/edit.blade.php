<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center space-x-2">
            <span>⚙️ Gerenciar Produto:</span>
            <span class="text-indigo-600 dark:text-indigo-400">{{ $produto->nome }}</span>
        </h2>
    </x-slot>

    {{-- ALERTA DE SUCESSO --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('sucesso'))
                <x-alert :message="session('sucesso')" />
            @endif
        </div>
    </div>

    <div class="pb-12" x-data="{ activeTab: 'variacoes', isModalOpen: false, currentVariation: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- NAVEGAÇÃO ENTRE TABS --}}
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
                <nav class="flex justify-around sm:justify-start border-b border-gray-200 dark:border-gray-700">
                    @foreach ([
                        'variacoes' => 'Variações e Estoque',
                        'gerais' => 'Dados Gerais',
                        'atributos' => 'Atributos do Produto'
                    ] as $tab => $label)
                        <button
                            @click="activeTab = '{{ $tab }}'"
                            :class="activeTab === '{{ $tab }}'
                                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- TAB: VARIAÇÕES --}}
            <div x-show="activeTab === 'variacoes'">
                <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-6">Variações de Produto (SKUs)</h3>

                        {{-- LISTAGEM --}}
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900/30">
                                    <tr>
                                        @foreach (['Atributos','SKU','Preço Venda','Estoque Atual','Estoque Mínimo','Ações'] as $col)
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                {{ $col }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($produto->variations as $variation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                            <td class="px-4 py-3 text-sm">
                                                @foreach ($variation->attributeValues as $value)
                                                    <span class="font-medium">{{ $value->atributo->nome }}:</span> {{ $value->valor }}@if (!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td class="px-4 py-3 text-sm">{{ $variation->sku }}</td>
                                            <td class="px-4 py-3 text-sm">R$ {{ number_format($variation->preco_venda, 2, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $variation->estoque_atual }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $variation->estoque_minimo }}</td>
                                            <td class="px-4 py-3 flex items-center gap-2">
                                                <a href="{{ route('variations.label', $variation->id) }}" target="_blank"
                                                   class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md font-semibold transition">Etiqueta</a>

                                                <button @click="isModalOpen = true; currentVariation = {{ $variation->toJson() }}"
                                                        class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded-md font-semibold transition">Editar</button>

                                                <form method="POST" action="{{ route('variations.destroy', $variation->id) }}"
                                                      onsubmit="return confirm('Tem certeza que deseja apagar esta variação?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-md font-semibold transition">
                                                        Apagar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                Nenhuma variação cadastrada.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- NOVA VARIAÇÃO --}}
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="font-semibold mb-4">➕ Adicionar Nova Variação</h4>
                            @if($produto->attributes->isNotEmpty())
                                <form method="POST" action="{{ route('variations.store', $produto->id) }}" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($produto->attributes as $atributo)
                                            <div>
                                                <label for="attribute_{{ $atributo->id }}" class="block font-medium text-sm mb-1">{{ $atributo->nome }}</label>
                                                <select name="attribute_values[]" id="attribute_{{ $atributo->id }}"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                                                    <option value="">Selecione...</option>
                                                    @foreach ($atributo->valorAtributos as $valor)
                                                        <option value="{{ $valor->id }}">{{ $valor->valor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        @foreach ([
                                            'sku' => 'SKU',
                                            'preco_custo' => 'Preço Custo',
                                            'preco_venda' => 'Preço Venda',
                                            'estoque_inicial' => 'Estoque Inicial',
                                            'estoque_minimo' => 'Estoque Mínimo'
                                        ] as $id => $label)
                                            <div>
                                                <label for="{{ $id }}" class="block font-medium text-sm mb-1">{{ $label }}</label>
                                                <input id="{{ $id }}" name="{{ $id }}" type="{{ in_array($id,['estoque_inicial','estoque_minimo']) ? 'number' : 'text' }}"
                                                       value="{{ in_array($id,['estoque_inicial','estoque_minimo']) ? 0 : '' }}"
                                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('produtos.index') }}"
                                        class="inline-flex items-center px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md shadow-sm text-sm font-medium transition ">
                                        ← Voltar para Produtos
                                        </a>

                                        <button type="submit"
                                            class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold text-sm transition">
                                            Salvar Variação
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    ⚠️ Adicione atributos na aba "Atributos do Produto" antes de criar variações.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: DADOS GERAIS --}}
            <div x-show="activeTab === 'gerais'" x-cloak>
                <x-gerais-produto
                    :produto="$produto"
                    :categorias="$categorias"
                    :marcas="$marcas"
                    :fornecedores="$fornecedores"
                />
            </div>

            {{-- TAB: ATRIBUTOS --}}
            <div x-show="activeTab === 'atributos'" x-cloak>
                <x-atributos-produto
                    :produto="$produto"
                    :todosAtributos="$todosAtributos"
                />
            </div>
        </div>

        {{-- MODAL EDIÇÃO VARIAÇÃO --}}
        <div x-show="isModalOpen"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
             @click.self="isModalOpen = false" x-cloak>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-2xl p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Editar Variação</h3>
                <form :action="`/variations/${currentVariation.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4 text-gray-800 dark:text-gray-200">
                        <div>
                            <label class="block text-sm font-medium">SKU</label>
                            <input type="text" name="sku" x-model="currentVariation.sku"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <template x-for="field in [
                                {id:'preco_custo', label:'Preço Custo'},
                                {id:'preco_venda', label:'Preço Venda'},
                                {id:'estoque_atual', label:'Estoque', disabled:true},
                                {id:'estoque_minimo', label:'Estoque Mínimo'}
                            ]" :key="field.id">
                                <div>
                                    <label class="block text-sm font-medium" x-text="field.label"></label>
                                    <input :type="field.id.includes('estoque') ? 'number':'text'"
                                           :name="field.id" :id="`edit_${field.id}`"
                                           x-model="currentVariation[field.id]"
                                           :disabled="field.disabled"
                                           class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                                    <template x-if="field.id==='estoque_atual'">
                                        <p class="text-xs text-gray-500 mt-1">Alteração apenas em Movimentar Estoque.</p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="isModalOpen = false"
                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 rounded-md text-sm font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold">
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
