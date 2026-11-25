<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Models\AnneeAcademique;
use App\Models\Favori;
use App\Models\Filiere;
use App\Models\Memoire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    public function index(Request $request): View
    {
        $memoires = Memoire::query()
            // Visible : validé et embargo passé ou vide.
            ->where('statut', StatutMemoire::Valide->value)
            ->where(function ($q) {
                $q->whereNull('date_embargo')->orWhereDate('date_embargo', '<=', today());
            })
            // Recherche plein texte (FULLTEXT titre, resume, mots_cles).
            ->when($request->filled('q'), function ($q) use ($request) {
                $termes = preg_split('/[^\p{L}\p{N}]+/u', (string) $request->input('q'), -1, PREG_SPLIT_NO_EMPTY);

                if ($termes) {
                    // Mode booléen : évite le seuil de 50 % du mode naturel sur peu de données.
                    $recherche = implode(' ', array_map(fn ($t) => $t.'*', $termes));
                    $q->whereRaw('MATCH(titre, resume, mots_cles) AGAINST (? IN BOOLEAN MODE)', [$recherche]);
                }
            })
            ->when($request->filled('auteur'), function ($q) use ($request) {
                $terme = '%'.$request->input('auteur').'%';

                $q->whereHas('auteurs', function ($a) use ($terme) {
                    $a->where(function ($w) use ($terme) {
                        $w->where('nom', 'like', $terme)
                          ->orWhere('prenom', 'like', $terme);
                    });
                });
            })
            ->when($request->filled('mot_cle'), fn ($q) => $q->where('mots_cles', 'like', '%'.$request->input('mot_cle').'%'))
            ->when($request->filled('filiere_id'), fn ($q) => $q->where('filiere_id', $request->input('filiere_id')))
            ->when($request->filled('annee_id'), fn ($q) => $q->where('annee_academique_id', $request->input('annee_id')))
            ->with(['auteurs', 'filiere', 'anneeAcademique'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('catalogue.index', [
            'memoires' => $memoires,
            'filieres' => Filiere::orderBy('libelle')->get(),
            'annees' => AnneeAcademique::orderByDesc('libelle')->get(),
        ]);
    }

    public function show(Request $request, Memoire $memoire): View
    {
        $this->autoriserVoir($request, $memoire);

        $memoire->load(['auteurs', 'filiere', 'anneeAcademique']);

        $estFavori = $request->user()
            ? Favori::where('user_id', $request->user()->id)->where('memoire_id', $memoire->id)->exists()
            : false;

        return view('catalogue.show', compact('memoire', 'estFavori'));
    }

    private function autoriserVoir(Request $request, Memoire $memoire): void
    {
        \Illuminate\Support\Facades\Gate::forUser($request->user())->authorize('voir', $memoire);
    }
}