<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class AlertaEstoqueBaixo extends Notification
{
    use Queueable;

    public Collection $variacoes;

    /**
     * Cria uma nova instância da notificação.
     */
    public function __construct(Collection $variacoesComEstoqueBaixo)
    {
        $this->variacoes = $variacoesComEstoqueBaixo;
    }

    /**
     * Define os canais de notificação (poderia ser 'slack', 'sms', etc.).
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Formata a mensagem de e-mail.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
                    ->subject('Alerta: Produtos com Stock Baixo!')
                    ->greeting('Olá!')
                    ->line('Os seguintes itens atingiram o nível mínimo de stock e precisam da sua atenção:');

        foreach ($this->variacoes as $variacao) {
            $nomeProduto = $variacao->produto->nome . ' (' . $variacao->sku . ')';
            $linha = "**{$nomeProduto}**: Stock Atual: **{$variacao->estoque_atual}**, Mínimo: **{$variacao->estoque_minimo}**";
            $mailMessage->line($linha);
        }

        $mailMessage->action('Ver Dashboard', route('dashboard'))
                    ->line('Por favor, verifique o sistema para mais detalhes.');

        return $mailMessage;
    }
}