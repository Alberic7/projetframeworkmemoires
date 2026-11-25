<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutMemoire;
use App\Http\Controllers\Controller;
use App\Models\Memoire;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function __invoke()
    {
        $compte = DB::table('memoires')
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // Les 4 statuts sont toujours présents (0 si aucun mémoire)
        $parStatut = collect(StatutMemoire::cases())
            ->mapWithKeys(fn ($s) => [$s->value => (int) ($compte[$s->value] ?? 0)]);

        $totalMemoires = $parStatut->sum();

        $plusLus = Memoire::withCount('consultations')
            ->having('consultations_count', '>', 0)
            ->orderByDesc('consultations_count')
            ->limit(10)
            ->get();

        $parFiliere = DB::table('filieres')
            ->leftJoin('memoires', 'memoires.filiere_id', '=', 'filieres.id')
            ->select('filieres.libelle', DB::raw('count(memoires.id) as total'))
            ->groupBy('filieres.id', 'filieres.libelle')
            ->orderBy('filieres.libelle')
            ->get();

        return view('admin.statistiques', compact('totalMemoires', 'parStatut', 'plusLus', 'parFiliere'));
    }
}