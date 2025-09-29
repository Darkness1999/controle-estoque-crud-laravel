<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta - {{ $variation->sku }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Regras específicas de impressão */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white !important;
            }
            .label-container {
                /* Mantém tamanho exato da etiqueta */
                width: 10cm !important;
                height: 5cm !important;
                /* Mantém a borda para corte */
                border: 1px dashed #444 !important;
                page-break-after: always;
            }
            .no-print { display: none; }
            .barcode-image, .barcode-image * {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <!-- Aviso antes da impressão -->
    <div class="no-print mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded text-center max-w-sm">
        <p class="font-semibold">A impressão deve iniciar automaticamente.</p>
        <p class="text-xs mt-1">Se não iniciar, clique em <strong>Imprimir</strong> ou pressione
            <kbd class="px-1 bg-gray-200 rounded">Ctrl</kbd> +
            <kbd class="px-1 bg-gray-200 rounded">P</kbd>.
        </p>
        <button onclick="window.print()"
                class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
            🖨️ Imprimir
        </button>
    </div>

    <!-- Etiqueta -->
    <div class="label-container bg-white rounded p-2 flex flex-col justify-between shadow-sm"
         style="width:10cm; height:5cm;">

        <!-- Produto -->
        <div>
            <p class="text-base font-semibold text-gray-800 leading-tight truncate">
                {{ $variation->produto->nome }}
            </p>
            <p class="text-[11px] text-gray-600 leading-snug mt-0.5">
                @foreach ($variation->attributeValues as $value)
                    <span>{{ $value->valor }}</span>@if (!$loop->last)<span class="mx-0.5">/</span>@endif
                @endforeach
            </p>
        </div>

        <!-- Código de Barras -->
        <div class="w-full text-center my-1 barcode-image">
            {!! DNS1D::getBarcodeHTML($variation->sku, 'C128', 1.4, 40, 'black', false) !!}
            <p class="text-[10px] font-mono tracking-widest mt-0.5 text-gray-700">{{ $variation->sku }}</p>
        </div>

        <!-- Preço -->
        <div class="text-right">
            <span class="text-[10px] text-gray-500 align-top">R$</span>
            <span class="text-xl font-bold text-gray-900">{{ number_format($variation->preco_venda, 2, ',', '.') }}</span>
        </div>

    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
