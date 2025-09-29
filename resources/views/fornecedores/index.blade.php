<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerenciamento de Fornecedores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Botão para adicionar fornecedor --}}
                    <div class="mb-6 flex justify-between items-center">
                        <p class="font-xl text-lg font-semibold">Lista de Fornecedores</p>
                        <a href="{{ route('fornecedores.create') }}"
                           class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            + Adicionar Fornecedor
                        </a>
                    </div>

                    {{-- Mensagem de sucesso --}}
                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    {{-- Lista de fornecedores --}}
                    @forelse ($fornecedores as $fornecedor)
                        <div class="py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition">

                            {{-- Informações do fornecedor --}}
                            <div class="space-y-1">
                                <p class="font-semibold text-lg">{{ $fornecedor->nome }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>CNPJ:</strong> {{ $fornecedor->cnpj ?? 'Não informado' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Email:</strong> {{ $fornecedor->email ?? 'Não informado' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Telefone:</strong> {{ $fornecedor->telefone ?? 'Não informado' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Endereço:</strong> {{ $fornecedor->endereco ?? 'Não informado' }}
                                </p>
                            </div>

                            {{-- Botões de ação --}}
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('fornecedores.show', $fornecedor->id) }}"
                                   class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs font-semibold hover:bg-blue-700 transition">
                                    Detalhes
                                </a>
                                <a href="{{ route('fornecedores.edit', $fornecedor->id) }}"
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md text-xs font-semibold hover:bg-yellow-600 transition">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('fornecedores.destroy', $fornecedor->id) }}"
                                      onsubmit="return confirm('Você tem certeza que deseja apagar este fornecedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded-md text-xs font-semibold hover:bg-red-700 transition">
                                        Apagar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 mt-4">
                            Nenhum fornecedor cadastrado.
                        </p>
                    @endforelse

                    {{-- Paginação --}}
                    <div class="mt-6">
                        {{ $fornecedores->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
