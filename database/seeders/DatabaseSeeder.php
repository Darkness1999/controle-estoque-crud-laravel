<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Fornecedor;
use App\Models\Cliente;
use App\Models\Atributo;
use App\Models\Produto;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Utilizadores
        $this->command->info('A criar utilizadores...');
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@stockpro.com',
            'role' => 'admin',
        ]);
        User::factory(5)->create();

        // 2. Criar Dados Mestres
        $this->command->info('A criar dados mestres (Categorias, Marcas, etc.)...');
        $categorias = Categoria::factory(10)->create();
        $marcas = Marca::factory(15)->create();
        $fornecedores = Fornecedor::factory(5)->create();
        $clientes = Cliente::factory(20)->create();

        // 3. Criar Atributos e seus Valores
        $atributoCor = Atributo::create(['nome' => 'Cor']);
        collect(['Azul', 'Preto', 'Branco', 'Vermelho', 'Verde', 'Amarelo'])->each(fn ($cor) => $atributoCor->valorAtributos()->create(['valor' => $cor]));
        $atributoTamanho = Atributo::create(['nome' => 'Tamanho']);
        collect(['P', 'M', 'G', 'GG'])->each(fn ($tamanho) => $atributoTamanho->valorAtributos()->create(['valor' => $tamanho]));
        
        // 4. Criar Produtos, Variações e Lotes de Estoque Inicial
        $this->command->info('A criar produtos e stock inicial...');
        $this->command->getOutput()->progressStart(50);
        
        Produto::factory(50)->make()->each(function ($produto) use ($categorias, $marcas, $fornecedores, $atributoCor, $atributoTamanho, $admin) {
            $produto->categoria_id = $categorias->random()->id;
            $produto->marca_id = $marcas->random()->id;
            $produto->fornecedor_id = $fornecedores->random()->id;
            $produto->save();

            $numVariations = rand(2, 5);
            for ($i = 0; $i < $numVariations; $i++) {
                $variation = $produto->variations()->create(ProductVariation::factory()->make()->toArray());
                $variation->attributeValues()->attach($atributoCor->valorAtributos->random()->id);
                $variation->attributeValues()->attach($atributoTamanho->valorAtributos->random()->id);

                $lote = $variation->lotesEstoque()->create(\App\Models\LoteEstoque::factory()->make()->toArray());

                // Regista a movimentação de ENTRADA inicial
                $variation->movimentacoes()->create([
                    'lote_estoque_id' => $lote->id,
                    'user_id' => $admin->id,
                    'tipo' => 'entrada',
                    'quantidade' => $lote->quantidade_atual,
                    'motivo' => 'Estoque Inicial (Seeder)',
                    'fornecedor_id' => $produto->fornecedor_id,
                ]);
            }
            $this->command->getOutput()->progressAdvance();
        });
        $this->command->getOutput()->progressFinish();

        // 5. SIMULAR VENDAS (HISTÓRICO DE SAÍDAS)
        $this->command->info("\n A simular histórico de vendas...");
        $variationsToSell = ProductVariation::has('lotesEstoque')->get()->random(30);

        foreach ($variationsToSell as $variation) {
            $numVendas = rand(5, 20);
            for ($i=0; $i < $numVendas; $i++) { 
                $variation->refresh(); // Recarrega a variação para obter o 'estoque_atual' correto
                if ($variation->estoque_atual > 0) {
                    $quantidadeVendida = rand(1, min(5, $variation->estoque_atual));
                    
                    // Prepara os dados COMPLETOS para o serviço
                    $dadosMovimentacao = [
                        'product_variation_id' => $variation->id,
                        'tipo' => 'saida',
                        'quantidade' => $quantidadeVendida,
                        'motivo' => 'Venda Simulada (Seeder)',
                        'cliente_id' => $clientes->random()->id,
                        'fornecedor_id' => null, // Nulo porque é uma saída
                        'lote' => null,          // Nulo porque o serviço de saída irá encontrá-lo
                        'data_validade' => null, // Nulo porque o serviço de saída irá encontrá-lo
                    ];
                    
                    $estoqueService = app(\App\Services\EstoqueService::class);
                    $estoqueService->registrarMovimentacao($dadosMovimentacao);
                }
            }
        }
        $this->command->info('Simulação de vendas concluída.');
    }
}