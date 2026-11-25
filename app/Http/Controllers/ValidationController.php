<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Http\Requests\RejetMemoireRequest;
use App\Http\Requests\ValiderMemoireRequest;
use App\Models\Memoire;
use App\Models\Validation;
use App\Notifications\MemoireRejeteNotification;
use App\Notifications\MemoireValideNotification;
use App\Services\PlagiatService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ValidationController extends Controller
{
    public function index()
    {
        $memoires = Memoire::whereIn('statut', [StatutMemoire::Soumis->value, StatutMemoire::EnVerification->value])
            ->with(['etudiant', 'filiere', 'anneeAcademique'])
            ->oldest()
            ->get();

        return view('validation.index', compact('memoires'));
    }

    public function show(Memoire $memoire, PlagiatService $plagiat)
    {
        Gate::authorize('valider', $memoire);

        // Transition soumis -> en_verification à l'ouverture du dossier
        Memoire::whereKey($memoire->id)
            ->where('statut', StatutMemoire::Soumis->value)
            ->update(['statut' => StatutMemoire::EnVerification->value]);

        $memoire->refresh()->load(['etudiant', 'filiere', 'anneeAcademique']);
        $similaires = $plagiat->analyser($memoire);

        return view('validation.show', compact('memoire', 'similaires'));
    }

    public function valider(ValiderMemoireRequest $request, Memoire $memoire)
    {
        Gate::authorize('valider', $memoire);

        $ok = DB::transaction(function () use ($request, $memoire) {
            // Mise à jour conditionnelle : seule la transition en_verification -> valide est permise
            $modifie = Memoire::whereKey($memoire->id)
                ->where('statut', StatutMemoire::EnVerification->value)
                ->update([
                    'statut' => StatutMemoire::Valide->value,
                    'motif_rejet' => null,
                    'telechargement_autorise' => $request->boolean('telechargement_autorise'),
                    'acces_restreint' => $request->boolean('acces_restreint'),
                    'date_embargo' => $request->validated('date_embargo'),
                ]);

            if (! $modifie) {
                return false;
            }

            Validation::forceCreate([
                'memoire_id' => $memoire->id,
                'responsable_id' => $request->user()->id,
                'decision' => StatutMemoire::Valide->value,
                'motif' => null,
            ]);

            return true;
        });

        if (! $ok) {
            return redirect()->route('validation.index')
                ->with('error', "Ce mémoire n'est plus en cours de vérification.");
        }

        $memoire->refresh();
        $memoire->etudiant->notify(new MemoireValideNotification($memoire));

        return redirect()->route('validation.index')->with('success', 'Mémoire validé.');
    }

    public function rejeter(RejetMemoireRequest $request, Memoire $memoire)
    {
        Gate::authorize('valider', $memoire);

        $motif = $request->validated('motif');

        $ok = DB::transaction(function () use ($request, $memoire, $motif) {
            $modifie = Memoire::whereKey($memoire->id)
                ->where('statut', StatutMemoire::EnVerification->value)
                ->update([
                    'statut' => StatutMemoire::Rejete->value,
                    'motif_rejet' => $motif,
                ]);

            if (! $modifie) {
                return false;
            }

            Validation::forceCreate([
                'memoire_id' => $memoire->id,
                'responsable_id' => $request->user()->id,
                'decision' => StatutMemoire::Rejete->value,
                'motif' => $motif,
            ]);

            return true;
        });

        if (! $ok) {
            return redirect()->route('validation.index')
                ->with('error', "Ce mémoire n'est plus en cours de vérification.");
        }

        $memoire->refresh();
        $memoire->etudiant->notify(new MemoireRejeteNotification($memoire));

        return redirect()->route('validation.index')->with('success', 'Mémoire rejeté. L\'étudiant a été notifié.');
    }
}