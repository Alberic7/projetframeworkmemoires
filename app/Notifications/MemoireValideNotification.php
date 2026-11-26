<?php

namespace App\Notifications;

use App\Models\Memoire;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MemoireValideNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Memoire $memoire) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Votre mémoire a été validé')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Votre mémoire **\"{$this->memoire->titre}\"** a été validé par la bibliothèque.")
            ->line("Il est désormais visible dans le catalogue public.")
            ->action('Voir le catalogue', url('/catalogue'))
            ->line("Merci pour votre contribution académique !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'memoire_valide',
            'memoire_id' => $this->memoire->id,
            'titre' => $this->memoire->titre,
            'message' => "Votre mémoire \"{$this->memoire->titre}\" a été validé.",
        ];
    }
}
