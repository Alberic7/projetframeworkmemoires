<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Http\Requests\StoreMemoireRequest;
use App\Models\AnneeAcademique;
use App\Models\Consultation;
use App\Models\Filiere;
use App\Models\Memoire;
use App\Services\PlagiatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemoireController extends Controller
{
    private const DISQUE = 'memoires'; // disque privé défini dans config/filesystems.php

    public function create()
    {
        $filieres = Filiere::orderBy('libelle')->get();
        $annees = AnneeAcademique::orderByDesc('libelle')->get();

        return view('memoires.create', compact('filieres', 'annees'));
    }

    public function store(StoreMemoireRequest $request, PlagiatService $plagiat)
    {
        $data = $request->validated();
        $chemin = $request->file('fichier_pdf')->store('', self::DISQUE);

        try {
            $memoire = Memoire::forceCreate([
                'titre' => $data['titre'],
                'resume' => $data['resume'],
                'mots_cles' => $data['mots_cles'],
                'encadreur' => $data['encadreur'],
                'filiere_id' => $data['filiere_id'],
                'annee_academique_id' => $data['annee_academique_id'],
                'fichier_pdf' => $chemin,
                'statut' => StatutMemoire::Soumis->value,
                'telechargement_autorise' => false,
                'acces_restreint' => false,
                'etudiant_id' => $request->user()->id,
            ]);

            $similaires = $plagiat->analyser($memoire);
            $memoire->forceFill(['score_similarite' => $similaires->max('score') ?? 0])->save();
        } catch (\Throwable $e) {
            Storage::disk(self::DISQUE)->delete($chemin); // pas de fichier orphelin
            throw $e;
        }

        return redirect()->route('mes-memoires')
            ->with('success', "Votre mémoire a été déposé. Il sera examiné par la bibliothèque.");
    }

    public function mesMemoires(Request $request)
    {
        $memoires = Memoire::where('etudiant_id', $request->user()->id)
            ->with(['filiere', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('memoires.mes-memoires', compact('memoires'));
    }

    public function lire(Request $request, Memoire $memoire)
    {
        Gate::authorize('lire', $memoire);

        Consultation::forceCreate([
            'memoire_id' => $memoire->id,
            'user_id' => $request->user()->id,
        ]);

        $memoire->load(['etudiant', 'filiere', 'anneeAcademique']);

        return view('memoires.lire', [
            'memoire' => $memoire,
            'urlPdf' => route('memoires.pdf', $memoire),
            'telechargementAutorise' => (bool) $memoire->telechargement_autorise,
        ]);
    }

    public function pdf(Memoire $memoire)
    {
        Gate::authorize('lire', $memoire);

        return $this->fluxPdf($memoire, 'inline');
    }

    public function telecharger(Memoire $memoire)
    {
        Gate::authorize('telecharger', $memoire);
        abort_unless($memoire->telechargement_autorise, 403, "Le téléchargement n'est pas autorisé pour ce mémoire.");

        return $this->fluxPdf($memoire, 'attachment');
    }

    private function fluxPdf(Memoire $memoire, string $disposition)
    {
        $disque = Storage::disk(self::DISQUE);
        abort_unless($memoire->fichier_pdf && $disque->exists($memoire->fichier_pdf), 404, 'Fichier introuvable.');

        $nom = (Str::slug(Str::limit($memoire->titre, 80, '')) ?: 'memoire').'.pdf';

        return $disque->response($memoire->fichier_pdf, $nom, ['Content-Type' => 'application/pdf'], $disposition);
    }
}