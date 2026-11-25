<?php

namespace App\Notifications;

use App\Models\Memoire;
use App\Models\User;
use Illuminate\Notifications\Notification;

class InvitationBinomeNotification extends Notification
{
    public function __construct(public Memoire $memoire, public User $deposant)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'memoire_id' => $this->memoire->id,
            'message' => "{$this->deposant->prenom} {$this->deposant->nom} vous invite à co-signer le mémoire « {$this->memoire->titre} ».",
            'url' => route('binome.invitations'),
        ];
    }
}