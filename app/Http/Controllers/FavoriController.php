<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use App\Models\Memoire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FavoriController extends Controller
{
    public function index(Request $request): View
    {
        $memoires = Memoire::query()
            ->whereIn('id', Favori::where('user_id', $request->user()->id)->select('memoire_id'))
            ->with(['auteurs', 'filiere', 'anneeAcademique'])
            ->latest()
            ->paginate(12);

        return view('favoris.index', compact('memoires'));
    }

    public function toggle(Request $request, Memoire $memoire): RedirectResponse
    {
        Gate::authorize('lire', $memoire);

        $favori = Favori::where('user_id', $request->user()->id)
            ->where('memoire_id', $memoire->id)
            ->first();

        if ($favori) {
            $favori->delete();

            return back()->with('success', 'Mémoire retiré des favoris.');
        }

        Favori::create([
            'user_id' => $request->user()->id,
            'memoire_id' => $memoire->id,
        ]);

        return back()->with('success', 'Mémoire ajouté aux favoris.');
    }
}