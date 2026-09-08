<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationPendingApprovalNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Registo recebido - Portal Cientifico')
            ->line('O seu registo foi recebido no Portal Cientifico.')
            ->line('Por favor confirme o seu email quando receber a mensagem de confirmacao.')
            ->line('Depois da confirmacao, aguarde a aprovacao do administrador.');
    }
}
