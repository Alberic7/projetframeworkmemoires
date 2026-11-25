<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\Models\Favori;
use App\Models\Filiere;
use App\Models\Memoire;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $memoires = Memoire::visibles()
            ->with(['etudiant', 'filiere', 'anneeAcademique'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim($request->input('q'));
                // FULLTEXT + repli LIKE sur le titre (mots courts ou ignorés par MySQL)
                $query->where(function ($sub) use ($q) {
                    $sub->whereRaw('MATCH(titre, resume, mots_cles) AGAINST (? IN NATURAL LANGUAGE MODE)', [$q])
                        ->orWhere('titre', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('auteur'), function ($query) use ($request) {
                $auteur = trim($request->input('auteur'));
                $query->whereHas('etudiant', function ($e) use ($auteur) {
                    $e->where(function ($x) use ($auteur) {
                        $x->where('nom', 'like', "%{$auteur}%")
                          ->orWhere('prenom', 'like', "%{$auteur}%");
                    });
                });
            })
            ->when($request->filled('mot_cle'), fn ($query) => $query->where('mots_cles', 'like', '%'.trim($request->input('mot_cle')).'%'))
            ->when($request->filled('filiere_id'), fn ($query) => $query->where('filiere_id', $request->integer('filiere_id')))
            ->when($request->filled('annee_id'), fn ($query) => $query->where('annee_academique_id', $request->integer('annee_id')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $filieres = Filiere::orderBy('libelle')->get();
        $annees = AnneeAcademique::orderByDesc('libelle')->get();

        return view('catalogue.index', compact('memoires', 'filieres', 'annees'));
    }

    public function show(Memoire $memoire)
    {
        // Un mémoire non validé ou sous embargo n'existe pas pour le public
        abort_unless(Memoire::visibles()->whereKey($memoire->getKey())->exists(), 404);

        $memoire->load(['etudiant', 'filiere', 'anneeAcademique']);

        $estFavori = auth()->check()
            && Favori::where('user_id', auth()->id())->where('memoire_id', $memoire->id)->exists();

        return view('catalogue.show', compact('memoire', 'estFavori'));
    }
}