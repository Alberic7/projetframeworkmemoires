<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Models\Memoire;
use App\Notifications\BinomeReponseNotification;
use App\Services\RegleDepotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BinomeController extends Controller
{
    public const MOTIF_REFUS = 'Le binôme a refusé la co-signature';

    /** Invitations en attente de l'étudiant connecté. */
    public function index(Request $request): View
    {
        $invitations = Memoire::query()
            ->where('statut', StatutMemoire::EnAttenteBinome->value)
            ->whereHas('auteurs', function ($q) use ($request) {
                $q->where('users.id', $request->user()->id)
                  ->whereNull('memoire_auteurs.confirme_le');
            })
            ->with(['auteurs', 'filiere', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('binome.invitations', compact('invitations'));
    }

    public function accepter(Request $request, Memoire $memoire, RegleDepotService $regle): RedirectResponse
    {
        $user = $request->user();

        // Règle « un mémoire par année » revérifiée à l'acceptation.
        if ($regle->aDejaUnMemoire($user, $memoire->annee_academique_id, $memoire->id)) {
            return back()->with('error', 'Vous êtes déjà auteur d’un mémoire pour cette année académique.');
        }

        $ok = DB::transaction(function () use ($memoire, $user) {
            // Mise à jour conditionnelle : le mémoire doit être encore en attente.
            $m = Memoire::whereKey($memoire->id)
                ->where('statut', StatutMemoire::EnAttenteBinome->value)
                ->lockForUpdate()
                ->first();

            if (! $m) {
                return null;
            }

            $confirme = DB::table('memoire_auteurs')
                ->where('memoire_id', $m->id)
                ->where('user_id', $user->id)
                ->whereNull('confirme_le')
                ->update(['confirme_le' => now(), 'updated_at' => now()]);

            if ($confirme === 0) {
                return null; // pas d'invitation en attente pour cet utilisateur
            }

            $m->update(['statut' => StatutMemoire::Soumis->value]);

            return $m;
        });

        if (! $ok) {
            return back()->with('error', 'Cette invitation n’est plus valable.');
        }

        $ok->etudiant->notify(new BinomeReponseNotification($ok, $user, true));

        return redirect()->route('mes-memoires')
            ->with('success', 'Vous avez accepté la co-signature. Le mémoire entre dans le circuit de validation.');
    }

    public function refuser(Request $request, Memoire $memoire): RedirectResponse
    {
        $user = $request->user();

        $ok = DB::transaction(function () use ($memoire, $user) {
            $m = Memoire::whereKey($memoire->id)
                ->where('statut', StatutMemoire::EnAttenteBinome->value)
                ->lockForUpdate()
                ->first();

            if (! $m) {
                return null;
            }

            $invitationEnAttente = DB::table('memoire_auteurs')
                ->where('memoire_id', $m->id)
                ->where('user_id', $user->id)
                ->whereNull('confirme_le')
                ->exists();

            if (! $invitationEnAttente) {
                return null;
            }

            $m->update([
                'statut' => StatutMemoire::Rejete->value,
                'motif_rejet' => self::MOTIF_REFUS,
            ]);

            return $m;
        });

        if (! $ok) {
            return back()->with('error', 'Cette invitation n’est plus valable.');
        }

        $ok->etudiant->notify(new BinomeReponseNotification($ok, $user, false));

        return redirect()->route('binome.invitations')
            ->with('success', 'Vous avez refusé la co-signature.');
    }
}