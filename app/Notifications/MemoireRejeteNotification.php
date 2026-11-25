<?php

namespace App\Notifications;

use App\Models\Memoire;
use Illuminate\Notifications\Notification;

class MemoireRejeteNotification extends Notification
{
    public function __construct(public Memoire $memoire)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'rejete',
            'memoire_id' => $this->memoire->id,
            'titre' => $this->memoire->titre,
            'motif' => $this->memoire->motif_rejet,
            'message' => "Votre mémoire « {$this->memoire->titre} » a été rejeté.",
        ];
    }
}