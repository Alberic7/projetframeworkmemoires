<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\StatutMemoire;
use Illuminate\Validation\Rule;

class StoreMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'resume' => ['required', 'string', 'max:5000'],
            'mots_cles' => ['required', 'string', 'max:255'],
            'encadreur' => ['required', 'string', 'max:150'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'annee_academique_id' => [
                'required',
                'exists:annees_academiques,id',
                Rule::unique('memoires', 'annee_academique_id')
                    ->where('etudiant_id', $this->user()->id)
                    ->whereNot('statut', StatutMemoire::Rejete->value),
            ],
            'fichier_pdf' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:20480'], // 20 Mo
        ];
    }

    public function attributes(): array
    {
        return [
            'titre' => 'titre',
            'resume' => 'résumé',
            'mots_cles' => 'mots-clés',
            'encadreur' => 'encadreur',
            'filiere_id' => 'filière',
            'annee_academique_id' => 'année académique',
            'fichier_pdf' => 'fichier PDF',
        ];
    }

    public function messages(): array
    {
        return [
            'fichier_pdf.mimes' => 'Le fichier doit être un PDF.',
            'fichier_pdf.mimetypes' => 'Le fichier doit être un PDF.',
            'fichier_pdf.max' => 'Le fichier ne doit pas dépasser 20 Mo.',
            'fichier_pdf.uploaded' => 'Le téléversement a échoué : le fichier est trop volumineux ou interrompu.',
            'annee_academique_id.unique' => "Vous avez déjà un mémoire pour cette année académique. Seul un mémoire rejeté peut être redéposé.",
        ];
    }
}