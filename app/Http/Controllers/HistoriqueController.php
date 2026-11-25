<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoriqueController extends Controller
{
    public function index(Request $request): View
    {
        $consultations = Consultation::query()
            ->where('user_id', $request->user()->id)
            ->with(['memoire.filiere', 'memoire.auteurs'])
            ->latest()
            ->paginate(15);

        return view('historique.index', compact('consultations'));
    }
}