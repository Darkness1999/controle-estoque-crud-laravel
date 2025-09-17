<?php

namespace App\Console\Commands;

use App\Models\ProductVariation;
use App\Models\User;
use App\Notifications\AlertaEstoqueBaixo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class VerificarNivelEstoque extends Command
{
    /**
     * A "assinatura" do nosso comando no terminal.
     */
    protected $signature = 'estoque:verificar-niveis';

    /**
     * A descrição do comando.
     */
    protected $description = 'Verifica as variações com nível de estoque baixo e notifica os administradores';

    /**
     * Lógica principal do comando.
     */
    public function handle()
    {
        $this->info('A iniciar verificação de níveis de stock...');

        // Encontra todas as variações onde o stock atual é menor ou igual ao mínimo
        $variacoesComEstoqueBaixo = ProductVariation::where('estoque_atual', '>', 0)
                                                   ->whereColumn('estoque_atual', '<=', 'estoque_minimo')
                                                   ->with('produto')
                                                   ->get();

        // Se encontrarmos alguma, notificamos
        if ($variacoesComEstoqueBaixo->isNotEmpty()) {
            $this->warn("Encontradas {$variacoesComEstoqueBaixo->count()} variações com stock baixo.");

            // Encontra os utilizadores que devem ser notificados (ex: todos os admins)
            $admins = User::where('role', 'admin')->get(); // Assumindo que temos uma coluna 'role'

            // Envia a notificação
            Notification::send($admins, new AlertaEstoqueBaixo($variacoesComEstoqueBaixo));

            $this->info('Notificações enviadas.');
        } else {
            $this->info('Nenhuma variação com stock baixo encontrada.');
        }

        return 0;
    }
}