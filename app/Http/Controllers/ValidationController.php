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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ValidationController extends Controller
{
    /** Mémoires à vérifier (jamais ceux en attente du binôme). */
    public function index(): View
    {
        $memoires = Memoire::query()
            ->whereIn('statut', [
                StatutMemoire::Soumis->value,
                StatutMemoire::EnVerification->value,
            ])
            ->with(['auteurs', 'filiere', 'anneeAcademique'])
            ->oldest()
            ->get();

        return view('validation.index', compact('memoires'));
    }

    public function show(Memoire $memoire, PlagiatService $plagiat): View
    {
        Gate::authorize('valider', $memoire);

        // soumis -> en_verification à l'ouverture de la fiche.
        Memoire::whereKey($memoire->id)
            ->where('statut', StatutMemoire::Soumis->value)
            ->update(['statut' => StatutMemoire::EnVerification->value]);

        $memoire->refresh()->load(['auteurs', 'filiere', 'anneeAcademique']);

        return view('validation.show', [
            'memoire' => $memoire,
            'similaires' => $plagiat->similaires($memoire),
        ]);
    }

    public function valider(ValiderMemoireRequest $request, Memoire $memoire): RedirectResponse
    {
        Gate::authorize('valider', $memoire);

        $modifie = DB::transaction(function () use ($request, $memoire) {
            $maj = Memoire::whereKey($memoire->id)
                ->whereIn('statut', [
                    StatutMemoire::Soumis->value,
                    StatutMemoire::EnVerification->value,
                ])
                ->update([
                    'statut' => StatutMemoire::Valide->value,
                    'motif_rejet' => null,
                    'telechargement_autorise' => $request->boolean('telechargement_autorise'),
                    'acces_restreint' => $request->boolean('acces_restreint'),
                    'date_embargo' => $request->input('date_embargo'),
                ]);

            if ($maj === 0) {
                return false;
            }

            Validation::create([
                'memoire_id' => $memoire->id,
                'responsable_id' => $request->user()->id,
                'decision' => StatutMemoire::Valide->value,
                'motif' => null,
            ]);

            return true;
        });

        if (! $modifie) {
            return redirect()->route('validation.index')
                ->with('error', 'Ce mémoire a déjà été traité.');
        }

        $memoire->refresh();
        Notification::send($memoire->auteursConfirmes, new MemoireValideNotification($memoire));

        return redirect()->route('validation.index')->with('success', 'Mémoire validé.');
    }

    public function rejeter(RejetMemoireRequest $request, Memoire $memoire): RedirectResponse
    {
        Gate::authorize('valider', $memoire);

        $motif = $request->input('motif');

        $modifie = DB::transaction(function () use ($request, $memoire, $motif) {
            $maj = Memoire::whereKey($memoire->id)
                ->whereIn('statut', [
                    StatutMemoire::Soumis->value,
                    StatutMemoire::EnVerification->value,
                ])
                ->update([
                    'statut' => StatutMemoire::Rejete->value,
                    'motif_rejet' => $motif,
                ]);

            if ($maj === 0) {
                return false;
            }

            Validation::create([
                'memoire_id' => $memoire->id,
                'responsable_id' => $request->user()->id,
                'decision' => StatutMemoire::Rejete->value,
                'motif' => $motif,
            ]);

            return true;
        });

        if (! $modifie) {
            return redirect()->route('validation.index')
                ->with('error', 'Ce mémoire a déjà été traité.');
        }

        $memoire->refresh();
        Notification::send($memoire->auteursConfirmes, new MemoireRejeteNotification($memoire));

        return redirect()->route('validation.index')->with('success', 'Mémoire rejeté.');
    }
}