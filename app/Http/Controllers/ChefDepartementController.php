<?php

namespace App\Http\Controllers;

use App\Enums\StatutMemoire;
use App\Models\Filiere;
use App\Models\Memoire;
use Illuminate\Http\Request;

class ChefDepartementController extends Controller
{
    public function index(Request $request)
    {
        $filiere = Filiere::find($request->user()->filiere_id);
        abort_unless($filiere, 403, "Aucune filière n'est rattachée à votre compte.");

        $memoires = Memoire::where('statut', StatutMemoire::Valide->value)
            ->where('filiere_id', $filiere->id)
            ->with(['etudiant', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('chef.memoires', compact('memoires', 'filiere'));
    }
}