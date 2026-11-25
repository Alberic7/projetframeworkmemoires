<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    public function index(Request $request)
    {
        $consultations = Consultation::where('user_id', $request->user()->id)
            ->with('memoire.filiere')
            ->latest()
            ->get();

        return view('historique.index', compact('consultations'));
    }
}