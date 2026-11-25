<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::orderBy('libelle')->get();

        return view('admin.filieres', compact('filieres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:150', 'unique:filieres,libelle'],
        ]);

        Filiere::forceCreate(['libelle' => $data['libelle']]);

        return redirect()->route('admin.filieres.index')->with('success', 'Filière ajoutée.');
    }

    public function update(Request $request, Filiere $filiere)
    {
        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:150', Rule::unique('filieres', 'libelle')->ignore($filiere->id)],
        ]);

        $filiere->forceFill(['libelle' => $data['libelle']])->save();

        return redirect()->route('admin.filieres.index')->with('success', 'Filière modifiée.');
    }

    public function destroy(Filiere $filiere)
    {
        try {
            $filiere->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Suppression impossible : cette filière est utilisée.');
        }

        return redirect()->route('admin.filieres.index')->with('success', 'Filière supprimée.');
    }
}