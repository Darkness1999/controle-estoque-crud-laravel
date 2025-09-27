@props(['title','value'=>'-','trend'=>null,'color'=>'indigo','warning'=>false,'icon'=>null])

<div class="p-6 rounded-xl shadow bg-white dark:bg-gray-800 hover:shadow-md transition">
    <div class="flex justify-between items-center">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h3>
        @if($icon)<span class="text-xl">{{ $icon }}</span>@endif
    </div>
    <div class="mt-2 flex items-baseline gap-2">
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</p>
        @if($trend)
            <span class="text-sm font-semibold {{ $trend['direcao']=='subiu' ? 'text-green-500' : 'text-red-500' }}">
                {{ $trend['direcao']=='subiu' ? '▲' : '▼' }}
                {{ number_format($trend['percentagem'],1,',') }}%
            </span>
        @endif
    </div>
    @if($warning)
        <p class="text-xs text-yellow-500 mt-1">Verifique imediatamente</p>
    @endif
</div>
