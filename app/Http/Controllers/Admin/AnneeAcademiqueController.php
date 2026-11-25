<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        $annees = AnneeAcademique::orderByDesc('libelle')->get();

        return view('admin.annees', compact('annees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:20', 'unique:annees_academiques,libelle'],
        ]);

        AnneeAcademique::forceCreate(['libelle' => $data['libelle']]);

        return redirect()->route('admin.annees.index')->with('success', 'Année académique ajoutée.');
    }

    public function update(Request $request, AnneeAcademique $annee)
    {
        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:20',
                Rule::unique('annees_academiques', 'libelle')->ignore($annee->id)],
        ]);

        $annee->forceFill(['libelle' => $data['libelle']])->save();

        return redirect()->route('admin.annees.index')->with('success', 'Année académique modifiée.');
    }

    public function destroy(AnneeAcademique $annee)
    {
        try {
            $annee->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Suppression impossible : cette année est utilisée.');
        }

        return redirect()->route('admin.annees.index')->with('success', 'Année académique supprimée.');
    }
}