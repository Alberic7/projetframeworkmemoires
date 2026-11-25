<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Http\Requests\StoreMemoireRequest;
use App\Models\AnneeAcademique;
use App\Models\Consultation;
use App\Models\Favori;
use App\Models\Filiere;
use App\Models\Memoire;
use App\Models\User;
use App\Notifications\InvitationBinomeNotification;
use App\Services\PlagiatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemoireController extends Controller
{
    private const DISQUE = 'prive';

    public function create(): View
    {
        return view('memoires.create', [
            'filieres' => Filiere::orderBy('libelle')->get(),
            'annees' => AnneeAcademique::orderByDesc('libelle')->get(),
        ]);
    }

    public function store(StoreMemoireRequest $request, PlagiatService $plagiat): RedirectResponse
    {
        $deposant = $request->user();
        $binome = $request->filled('binome_matricule')
            ? User::where('matricule', $request->input('binome_matricule'))->firstOrFail()
            : null;

        $chemin = null;

        try {
            $memoire = DB::transaction(function () use ($request, $plagiat, $deposant, $binome, &$chemin) {
                $chemin = $request->file('fichier_pdf')->store('memoires', self::DISQUE);

                $memoire = Memoire::create([
                    'titre' => $request->input('titre'),
                    'resume' => $request->input('resume'),
                    'mots_cles' => $request->input('mots_cles'),
                    'encadreur' => $request->input('encadreur'),
                    'filiere_id' => $request->input('filiere_id'),
                    'annee_academique_id' => $request->input('annee_academique_id'),
                    'fichier_pdf' => $chemin,
                    'score_similarite' => $plagiat->score(
                        (string) $request->input('titre'),
                        (string) $request->input('resume')
                    ),
                    'etudiant_id' => $deposant->id,
                    'statut' => $binome
                        ? StatutMemoire::EnAttenteBinome->value
                        : StatutMemoire::Soumis->value,
                ]);

                $memoire->auteurs()->attach($deposant->id, ['confirme_le' => now()]);

                if ($binome) {
                    $memoire->auteurs()->attach($binome->id, ['confirme_le' => null]);
                }

                return $memoire;
            });
        } catch (\Throwable $e) {
            if ($chemin) {
                Storage::disk(self::DISQUE)->delete($chemin);
            }
            throw $e;
        }

        if ($binome) {
            $binome->notify(new InvitationBinomeNotification($memoire, $deposant));

            return redirect()->route('mes-memoires')
                ->with('success', 'Mémoire déposé. Il sera soumis à validation dès que votre binôme aura accepté.');
        }

        return redirect()->route('mes-memoires')->with('success', 'Mémoire déposé avec succès.');
    }

    public function mesMemoires(Request $request): View
    {
        $memoires = Memoire::query()
            ->whereHas('auteurs', function ($q) use ($request) {
                $q->where('users.id', $request->user()->id)
                  ->whereNotNull('memoire_auteurs.confirme_le');
            })
            ->with(['auteurs', 'filiere', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('memoires.mes-memoires', compact('memoires'));
    }

    public function annuler(Request $request, Memoire $memoire): RedirectResponse
    {
        // Seul le déposant peut annuler.
        abort_unless($memoire->etudiant_id === $request->user()->id, 403);

        $fichier = DB::transaction(function () use ($memoire) {
            // Suppression conditionnelle : uniquement tant que le binôme n'a pas répondu.
            $m = Memoire::whereKey($memoire->id)
                ->where('statut', StatutMemoire::EnAttenteBinome->value)
                ->lockForUpdate()
                ->first();

            if (! $m) {
                return null;
            }

            Consultation::where('memoire_id', $m->id)->delete();
            Favori::where('memoire_id', $m->id)->delete();

            DatabaseNotification::where('type', InvitationBinomeNotification::class)
                ->where('data->memoire_id', $m->id)
                ->delete();

            $chemin = $m->fichier_pdf;
            $m->delete(); // memoire_auteurs supprimé en cascade

            return $chemin;
        });

        if ($fichier === null) {
            return back()->with('error', 'Ce mémoire ne peut plus être annulé.');
        }

        Storage::disk(self::DISQUE)->delete($fichier);

        return redirect()->route('mes-memoires')->with('success', 'Dépôt annulé.');
    }

    public function lire(Request $request, Memoire $memoire): View
    {
        Gate::authorize('lire', $memoire);

        // Chaque ouverture de la page de lecture est enregistrée (statistiques, historique).
        Consultation::create([
            'memoire_id' => $memoire->id,
            'user_id' => $request->user()->id,
        ]);

        $memoire->load(['auteurs', 'filiere', 'anneeAcademique']);

        return view('memoires.lire', [
            'memoire' => $memoire,
            'urlPdf' => route('memoires.pdf', $memoire),
            'telechargementAutorise' => Gate::allows('telecharger', $memoire),
        ]);
    }

    public function pdf(Memoire $memoire): StreamedResponse
    {
        Gate::authorize('lire', $memoire);

        abort_unless(Storage::disk(self::DISQUE)->exists($memoire->fichier_pdf), 404);

        return Storage::disk(self::DISQUE)->response(
            $memoire->fichier_pdf,
            'memoire-'.$memoire->id.'.pdf',
            ['Content-Type' => 'application/pdf'],
            'inline'
        );
    }

    public function telecharger(Memoire $memoire): StreamedResponse
    {
        Gate::authorize('telecharger', $memoire);

        abort_unless(Storage::disk(self::DISQUE)->exists($memoire->fichier_pdf), 404);

        return Storage::disk(self::DISQUE)->download(
            $memoire->fichier_pdf,
            Str::slug($memoire->titre).'.pdf'
        );
    }
}