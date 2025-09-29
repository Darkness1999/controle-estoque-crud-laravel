<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestão de Utilizadores') }}
        </h2>
    </x-slot>

    <div class="py-12 print:py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 🔹 Caixa com borda para impressão -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-300 dark:border-gray-700 print:border print:shadow-none print:rounded-none">
                <div class="p-6 text-gray-900 dark:text-gray-100 print:p-4">

                    @if (session('sucesso'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded print:border-green-600 print:bg-green-50">
                            {{ session('sucesso') }}
                        </div>
                    @endif

                    <!-- 🔹 Tabela Responsiva -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 dark:border-gray-700 print:border">
                            <thead class="border-b bg-gray-100 dark:bg-gray-700 print:bg-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Nome</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Email</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Função (Role)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b border-gray-300 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('users.update', $user->id) }}" method="POST" class="print:hidden">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex items-center space-x-2">
                                                    <select name="role" class="text-sm rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                                        <option value="operador" @if($user->role == 'operador') selected @endif>Operador</option>
                                                        <option value="admin" @if($user->role == 'admin') selected @endif>Administrador</option>
                                                    </select>
                                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700">
                                                        Salvar
                                                    </button>
                                                </div>
                                            </form>
                                            <!-- 🔹 Versão somente leitura para impressão -->
                                            <span class="hidden print:inline text-sm font-medium">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- 🔹 Paginação (oculta na impressão) -->
                    <div class="mt-4 print:hidden">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
