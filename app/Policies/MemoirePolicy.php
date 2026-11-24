<?php

namespace App\Policies;

use App\Enums\Role;
use App\Enums\StatutMemoire;
use App\Models\Memoire;
use App\Models\User;
use Illuminate\Support\Carbon;

class MemoirePolicy
{
    // Fiche du catalogue (visiteur autorisé)
    public function voir(?User $user, Memoire $memoire): bool
    {
        return $this->estPublie($memoire);
    }

    // Lecture en ligne et flux PDF
    public function lire(User $user, Memoire $memoire): bool
    {
        // Le responsable doit pouvoir ouvrir le PDF pour vérifier un dépôt
        if ($this->role($user) === Role::Responsable->value) {
            return true;
        }

        if (! $this->estPublie($memoire)) {
            return false;
        }

        if (! $memoire->acces_restreint) {
            return true;
        }

        // Accès restreint : l'auteur ou le chef de la filière concernée
        return $memoire->etudiant_id == $user->id
            || ($this->role($user) === Role::ChefDepartement->value
                && $user->filiere_id == $memoire->filiere_id);
    }

    public function telecharger(User $user, Memoire $memoire): bool
    {
        return $this->lire($user, $memoire) && (bool) $memoire->telechargement_autorise;
    }

    // Valider ou rejeter (même règle pour les deux)
    public function valider(User $user, Memoire $memoire): bool
    {
        return $this->role($user) === Role::Responsable->value
            && $this->statut($memoire) === StatutMemoire::EnVerification->value;
    }

    private function estPublie(Memoire $memoire): bool
    {
        return $this->statut($memoire) === StatutMemoire::Valide->value
            && ($memoire->date_embargo === null
                || Carbon::parse($memoire->date_embargo)->lte(now()));
    }

    private function role(User $user): string
    {
        return $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
    }

    private function statut(Memoire $memoire): string
    {
        return $memoire->statut instanceof \BackedEnum ? $memoire->statut->value : (string) $memoire->statut;
    }
}