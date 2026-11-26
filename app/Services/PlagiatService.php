<?php

namespace App\Services;

use App\Models\Memoire;
use Illuminate\Support\Collection;

/**
 * Service de détection de similarité de mémoires.
 * Version stub — retourne une collection vide.
 * À remplacer par une intégration réelle (ex: comparaison TF-IDF, API externe).
 */
class PlagiatService
{
    /**
     * Analyse un mémoire et retourne une collection de mémoires similaires
     * avec leur score de similarité.
     *
     * @param  Memoire  $memoire
     * @return Collection<array{memoire: Memoire, score: float}>
     */
    public function analyser(Memoire $memoire): Collection
    {
        // Stub : pas de détection pour l'instant — score 0 par défaut
        return collect();
    }
}
