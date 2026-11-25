<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Models\Filiere;
use App\Models\Memoire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChefDepartementController extends Controller
{
    /** Mémoires validés de la filière du chef de département. */
    public function index(Request $request): View
    {
        abort_unless($request->user()->filiere_id, 403);

        $filiere = Filiere::findOrFail($request->user()->filiere_id);

        $memoires = Memoire::query()
            ->where('statut', StatutMemoire::Valide->value)
            ->where('filiere_id', $filiere->id)
            ->with(['auteurs', 'anneeAcademique'])
            ->latest()
            ->paginate(15);

        return view('chef.memoires', compact('memoires', 'filiere'));
    }
}