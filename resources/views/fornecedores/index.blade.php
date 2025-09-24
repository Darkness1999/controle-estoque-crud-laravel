<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Fornecedores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('fornecedores.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Adicionar Fornecedor
                        </a>
                    </div>

                    <p>Lista de Fornecedores:</p>

                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    @forelse ($fornecedores as $fornecedor)
                    <div class="py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        {{-- Informações do Fornecedor --}}
                        <div class="space-y-1">
                            <p class="font-semibold text-lg">{{ $fornecedor->nome }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>CNPJ:</strong> {{ $fornecedor->cnpj ?? 'Não informado' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ $fornecedor->email ?? 'Não informado' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Telefone:</strong> {{ $fornecedor->telefone ?? 'Não informado' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Endereço:</strong> {{ $fornecedor->endereco ?? 'Não informado' }}</p> </div>

                        {{-- Botões de Ação --}}
                        <div class="flex space-x-2">
                            <td class="px-6 py-4 flex items-center space-x-4">
                                <a href="{{ route('fornecedores.show', $fornecedor->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md text-xs uppercase font-semibold hover:bg-blue-900 ">Detalhes</a>

                            </td>
                            
                            <a href="{{ route('fornecedores.edit', $fornecedor->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md text-xs uppercase font-semibold hover:bg-yellow-600">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('fornecedores.destroy', $fornecedor->id) }}" onsubmit="return confirm('Você tem certeza que deseja apagar este fornecedor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md text-xs uppercase font-semibold hover:bg-red-700">
                                    Apagar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Nenhum fornecedor cadastrado.</p>
                @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>