<?php

namespace App\Policies;

use App\Enums\StatutMemoire;
use App\Models\Memoire;
use App\Models\User;
use Carbon\Carbon;

class MemoirePolicy
{
    /** Fiche du catalogue (visiteurs inclus) : mémoire validé, embargo levé ou absent. */
    public function voir(?User $user, Memoire $memoire): bool
    {
        return $this->estPublic($memoire);
    }

    /** Lecture en ligne et flux PDF. */
    public function lire(User $user, Memoire $memoire): bool
    {
        if ($this->role($user) === 'responsable'
            || $this->estAuteur($user, $memoire)
            || $this->estInvite($user, $memoire)) {
            return true;
        }

        if (! $this->estPublic($memoire)) {
            return false;
        }

        if (! $memoire->acces_restreint) {
            return true;
        }

        // Accès restreint : le responsable et l'auteur (ci-dessus) + le chef de la filière concernée.
        return $this->role($user) === 'chef_departement'
            && $user->filiere_id === $memoire->filiere_id;
    }

    /** Téléchargement : refusé si telechargement_autorise est faux, et à l'invité avant sa réponse. */
    public function telecharger(User $user, Memoire $memoire): bool
    {
        return (bool) $memoire->telechargement_autorise
            && ! $this->estInvite($user, $memoire)
            && $this->lire($user, $memoire);
    }

    /** Validation ou rejet : responsable, sur un mémoire soumis ou en vérification. */
    public function valider(User $user, Memoire $memoire): bool
    {
        return $this->role($user) === 'responsable'
            && in_array($this->statut($memoire), [
                StatutMemoire::Soumis->value,
                StatutMemoire::EnVerification->value,
            ], true);
    }

    /** Auteur confirmé (déposant ou binôme ayant accepté). */
    private function estAuteur(User $user, Memoire $memoire): bool
    {
        return $memoire->auteurs()
            ->where('users.id', $user->id)
            ->whereNotNull('memoire_auteurs.confirme_le')
            ->exists();
    }

    /** Étudiant invité, invitation encore en attente. */
    private function estInvite(User $user, Memoire $memoire): bool
    {
        return $this->statut($memoire) === StatutMemoire::EnAttenteBinome->value
            && $memoire->auteurs()
                ->where('users.id', $user->id)
                ->whereNull('memoire_auteurs.confirme_le')
                ->exists();
    }

    private function estPublic(Memoire $memoire): bool
    {
        return $this->statut($memoire) === StatutMemoire::Valide->value
            && (! $memoire->date_embargo || Carbon::parse($memoire->date_embargo)->lte(today()));
    }

    private function statut(Memoire $memoire): string
    {
        return $memoire->statut instanceof \BackedEnum ? $memoire->statut->value : (string) $memoire->statut;
    }

    private function role(User $user): string
    {
        return $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
    }
}