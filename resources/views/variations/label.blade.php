<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta - {{ $variation->sku }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Estilos específicos para impressão */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .label-container {
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
            .no-print {
                display: none;
            }
            /* AQUI A CORREÇÃO MÁGICA */
            .barcode-image, .barcode-image * {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact; /* Para compatibilidade com Chrome/Safari */
            }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex flex-col items-center justify-center min-h-screen">

    <div class="no-print mb-4 p-4 bg-blue-100 text-blue-800 rounded-lg text-center">
        <p>A impressão deve iniciar automaticamente.</p>
        <p class="text-xs">Se não iniciar, clique em "Imprimir" ou pressione Ctrl+P.</p>
        <button onclick="window.print()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md">Imprimir</button>
    </div>

    <div class="label-container bg-white shadow-lg p-3 flex flex-col justify-between" style="width: 10cm; height: 5cm;">
        
        <div class="text-left">
            <p class="text-lg font-bold truncate">{{ $variation->produto->nome }}</p>
            <p class="text-xs text-gray-600">
                @foreach ($variation->attributeValues as $value)
                    <span>{{ $value->valor }}</span>@if (!$loop->last)<span class="mx-1">/</span>@endif
                @endforeach
            </p>
        </div>

        <div class="w-full text-center my-1 barcode-image">
            {!! DNS1D::getBarcodeHTML($variation->sku, 'C128', 1.5, 45, 'black', false) !!}
            <p class="text-xs font-mono tracking-widest mt-1">{{ $variation->sku }}</p>
        </div>

        <div class="text-right">
            <span class="text-xs">R$</span>
            <span class="text-2xl font-bold">{{ number_format($variation->preco_venda, 2, ',', '.') }}</span>
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>