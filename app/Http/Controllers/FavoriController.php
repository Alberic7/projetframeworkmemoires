<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use App\Models\Memoire;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    public function index(Request $request)
    {
        $memoires = Memoire::visibles()
            ->whereIn('id', Favori::where('user_id', $request->user()->id)->select('memoire_id'))
            ->with(['etudiant', 'filiere', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('favoris.index', compact('memoires'));
    }

    public function toggle(Request $request, Memoire $memoire)
    {
        $retires = Favori::where('user_id', $request->user()->id)
            ->where('memoire_id', $memoire->id)
            ->delete();

        if ($retires) {
            return back()->with('success', 'Mémoire retiré des favoris.');
        }

        abort_unless(Memoire::visibles()->whereKey($memoire->id)->exists(), 404);

        Favori::forceCreate([
            'user_id' => $request->user()->id,
            'memoire_id' => $memoire->id,
        ]);

        return back()->with('success', 'Mémoire ajouté aux favoris.');
    }
}