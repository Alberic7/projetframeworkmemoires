<?php

namespace App\Services;

use App\Enums\StatutMemoire;
use App\Models\Memoire;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlagiatService
{
    private const POIDS_TITRE = 0.4;
    private const POIDS_RESUME = 0.6;
    private const SEUIL = 10.0; // en dessous, la similarité n'est pas retenue

    private const MOTS_VIDES = [
        'les', 'des', 'une', 'aux', 'par', 'pour', 'sur', 'dans', 'avec', 'sans', 'est', 'sont',
        'que', 'qui', 'dont', 'cette', 'ces', 'son', 'ses', 'leur', 'leurs', 'nous', 'vous',
        'ils', 'elles', 'pas', 'plus', 'mais', 'donc', 'entre', 'vers', 'chez', 'comme',
        'ainsi', 'afin', 'etre', 'avoir', 'fait', 'faire', 'ete', 'aussi', 'tout', 'tous',
    ];

    /**
     * Compare le titre et le résumé du mémoire à ceux des autres mémoires
     * et renvoie les plus proches : [['memoire' => Memoire, 'score' => float], ...]
     * triés par score décroissant (score de 0 à 100).
     */
    public function analyser(Memoire $memoire, int $limite = 5): Collection
    {
        $titre = $this->mots($memoire->titre);
        $resume = $this->mots($memoire->resume);

        $scores = [];

        // Les mémoires rejetés sont ignorés : un étudiant qui redépose après un rejet
        // serait sinon comparé à sa propre version précédente.
        Memoire::where('id', '!=', $memoire->id)
            ->where('statut', '!=', StatutMemoire::Rejete->value)
            ->select(['id', 'titre', 'resume'])
            ->cursor()
            ->each(function ($autre) use ($titre, $resume, &$scores) {
                $score = 100 * (
                    self::POIDS_TITRE * $this->jaccard($titre, $this->mots($autre->titre))
                    + self::POIDS_RESUME * $this->jaccard($resume, $this->mots($autre->resume))
                );

                if ($score >= self::SEUIL) {
                    $scores[$autre->id] = round($score, 1);
                }
            });

        arsort($scores);
        $scores = array_slice($scores, 0, $limite, true);

        $memoires = Memoire::with('etudiant')->whereIn('id', array_keys($scores))->get()->keyBy('id');

        return collect($scores)->map(fn ($score, $id) => [
            'memoire' => $memoires[$id],
            'score' => $score,
        ])->values();
    }

    /** Ensemble des mots significatifs (clés du tableau), sans accents ni mots vides. */
    private function mots(?string $texte): array
    {
        $mots = preg_split('/[^a-z0-9]+/', Str::lower(Str::ascii((string) $texte)), -1, PREG_SPLIT_NO_EMPTY);

        $mots = array_filter($mots, fn ($m) => strlen($m) > 2 && ! in_array($m, self::MOTS_VIDES, true));

        return array_flip(array_unique($mots));
    }

    /** Indice de Jaccard : |A ∩ B| / |A ∪ B|, compris entre 0 et 1. */
    private function jaccard(array $a, array $b): float
    {
        if (! $a || ! $b) {
            return 0.0;
        }

        $intersection = count(array_intersect_key($a, $b));
        $union = count($a) + count($b) - $intersection;

        return $intersection / $union;
    }
}