<?php

namespace App\Notifications;

use App\Models\Memoire;
use Illuminate\Notifications\Notification;

class MemoireValideNotification extends Notification
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
            'type' => 'valide',
            'memoire_id' => $this->memoire->id,
            'titre' => $this->memoire->titre,
            'message' => "Votre mémoire « {$this->memoire->titre} » a été validé.",
        ];
    }
}