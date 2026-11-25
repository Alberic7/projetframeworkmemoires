<?php

namespace App\Notifications;

use App\Models\Memoire;
use App\Models\User;
use Illuminate\Notifications\Notification;

class BinomeReponseNotification extends Notification
{
    public function __construct(
        public Memoire $memoire,
        public User $binome,
        public bool $accepte
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $nom = "{$this->binome->prenom} {$this->binome->nom}";

        $message = $this->accepte
            ? "{$nom} a accepté de co-signer le mémoire « {$this->memoire->titre} ». Il entre dans le circuit de validation."
            : "{$nom} a refusé de co-signer le mémoire « {$this->memoire->titre} ». Le mémoire est rejeté, vous pouvez le redéposer.";

        return [
            'memoire_id' => $this->memoire->id,
            'message' => $message,
            'url' => route('mes-memoires'),
        ];
    }
}