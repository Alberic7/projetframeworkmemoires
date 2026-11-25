<?php

namespace App\Services;

use App\Enums\StatutMemoire;
use App\Models\Memoire;
use Illuminate\Support\Collection;

/**
 * Détection de plagiat simplifiée : similarité de Jaccard (en %)
 * entre les mots (4 lettres et plus) du titre + résumé.
 */
class PlagiatService
{
    /** Score le plus élevé (0 à 100) face aux mémoires existants non rejetés. */
    public function score(string $titre, string $resume, ?int $ignorerMemoireId = null): float
    {
        return (float) ($this->comparer($titre, $resume, $ignorerMemoireId)->max('score') ?? 0);
    }

    /** Mémoires proches, du plus au moins similaire : [['memoire' => Memoire, 'score' => float], ...] */
    public function similaires(Memoire $memoire, int $limite = 5): Collection
    {
        $scores = $this->comparer((string) $memoire->titre, (string) $memoire->resume, $memoire->id)
            ->filter(fn ($s) => $s['score'] > 0)
            ->sortByDesc('score')
            ->take($limite);

        $memoires = Memoire::with('auteurs')
            ->whereIn('id', $scores->pluck('id'))
            ->get()
            ->keyBy('id');

        return $scores
            ->map(fn ($s) => ['memoire' => $memoires[$s['id']], 'score' => $s['score']])
            ->values();
    }

    private function comparer(string $titre, string $resume, ?int $ignorerMemoireId): Collection
    {
        $reference = $this->jetons($titre.' '.$resume);

        return Memoire::query()
            ->select(['id', 'titre', 'resume'])
            ->where('statut', '!=', StatutMemoire::Rejete->value)
            ->when($ignorerMemoireId, fn ($q) => $q->where('id', '!=', $ignorerMemoireId))
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'score' => $this->jaccard($reference, $this->jetons($m->titre.' '.$m->resume)),
            ]);
    }

    private function jetons(string $texte): array
    {
        $mots = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($texte), -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_filter($mots, fn ($m) => mb_strlen($m) >= 4)));
    }

    private function jaccard(array $a, array $b): float
    {
        if (! $a || ! $b) {
            return 0.0;
        }

        $intersection = count(array_intersect($a, $b));
        $union = count(array_unique(array_merge($a, $b)));

        return round($intersection / $union * 100, 2);
    }
}