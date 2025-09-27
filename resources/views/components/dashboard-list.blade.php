@props(['title','items'=>[],'type'=>null])

<div class="p-6 rounded-xl shadow bg-white dark:bg-gray-800">
    <h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>
    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-64 overflow-y-auto">
        @forelse($items as $item)
            <li class="py-3 text-sm text-gray-700 dark:text-gray-200">
                {{-- Personalize conforme $type --}}
                @if($type === 'mov')
                    <span class="font-bold {{ $item->tipo === 'entrada' ? 'text-green-500' : 'text-red-500' }}">
                        {{ $item->quantidade }} un.
                    </span>
                    {{ $item->tipo }} –
                    {{ $item->productVariation?->produto?->nome ?? 'Produto Removido' }}
                    <div class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</div>
                @else
                    {{ $item->produto?->nome ?? 'Produto Removido' }}
                    <span class="text-xs text-gray-400">
                        (Estoque: {{ $item->estoque_atual ?? '-' }})
                    </span>
                @endif
            </li>
        @empty
            <li class="py-3 text-center text-gray-400">Nenhum registro encontrado.</li>
        @endforelse
    </ul>
</div>
