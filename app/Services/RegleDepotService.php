<?php

namespace App\Services;

use App\Enums\StatutMemoire;
use App\Models\Memoire;
use App\Models\User;

class RegleDepotService
{
    /**
     * L'étudiant est-il déjà auteur CONFIRMÉ d'un mémoire non rejeté pour cette année ?
     * Une invitation en attente ne compte pas ; un mémoire rejeté non plus.
     */
    public function aDejaUnMemoire(User|int $etudiant, int $anneeAcademiqueId, ?int $ignorerMemoireId = null): bool
    {
        $userId = $etudiant instanceof User ? $etudiant->id : $etudiant;

        return Memoire::query()
            ->where('annee_academique_id', $anneeAcademiqueId)
            ->where('statut', '!=', StatutMemoire::Rejete->value)
            ->when($ignorerMemoireId, fn ($q) => $q->where('id', '!=', $ignorerMemoireId))
            ->whereHas('auteurs', function ($q) use ($userId) {
                $q->where('users.id', $userId)
                  ->whereNotNull('memoire_auteurs.confirme_le');
            })
            ->exists();
    }
}