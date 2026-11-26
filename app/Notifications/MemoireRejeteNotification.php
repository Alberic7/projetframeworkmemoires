<?php

namespace App\Notifications;

use App\Models\Memoire;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MemoireRejeteNotification extends Notification
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
            ->subject('❌ Votre mémoire a été rejeté')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Votre mémoire **\"{$this->memoire->titre}\"** a été rejeté par la bibliothèque.")
            ->line("Motif : " . ($this->memoire->motif_rejet ?? 'Non précisé.'))
            ->action('Voir mes mémoires', url('/mes-memoires'))
            ->line("Vous pouvez corriger votre travail et le re-soumettre.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'memoire_rejete',
            'memoire_id' => $this->memoire->id,
            'titre' => $this->memoire->titre,
            'motif' => $this->memoire->motif_rejet,
            'message' => "Votre mémoire \"{$this->memoire->titre}\" a été rejeté.",
        ];
    }
}
