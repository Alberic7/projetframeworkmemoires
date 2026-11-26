<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->user()?->role instanceof \BackedEnum ? $this->user()->role->value : $this->user()?->role;

        return $this->user() !== null && $role === Role::Etudiant->value;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'resume' => ['required', 'string', 'min:20'],
            'mots_cles' => ['required', 'string', 'max:255'],
            'encadreur' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'integer', 'exists:filieres,id'],
            'annee_academique_id' => ['required', 'integer', 'exists:annees_academiques,id'],
            'fichier_pdf' => ['required', 'file', 'mimes:pdf', 'max:25600'], // max 25Mo
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre du mémoire est obligatoire.',
            'resume.required' => 'Le résumé est obligatoire (au moins 20 caractères).',
            'resume.min' => 'Le résumé doit comporter au moins 20 caractères.',
            'mots_cles.required' => 'Les mots-clés sont obligatoires.',
            'encadreur.required' => 'Le nom de l\'encadreur est obligatoire.',
            'filiere_id.required' => 'Veuillez sélectionner votre filière.',
            'annee_academique_id.required' => 'Veuillez sélectionner l\'année académique.',
            'fichier_pdf.required' => 'Le fichier PDF du mémoire est obligatoire.',
            'fichier_pdf.mimes' => 'Le fichier doit impérativement être au format PDF.',
            'fichier_pdf.max' => 'La taille du fichier PDF ne doit pas dépasser 25 Mo.',
        ];
    }
}
