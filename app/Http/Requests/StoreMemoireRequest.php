<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\StatutMemoire;
use Illuminate\Validation\Rule;
use App\Enums\Role;
use App\Models\User;
use App\Services\RegleDepotService;
use Illuminate\Contracts\Validation\Validator;

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
                'exists:annees_academiques,id',],
            'fichier_pdf' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:20480'], // 20 Mo
            'binome_matricule' => [
                'nullable',
                'string',
                Rule::exists('users', 'matricule')->where('role', Role::Etudiant->value),
                Rule::notIn(array_filter([$this->user()?->matricule])),
            ],
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
            'binome_matricule.exists' => 'Aucun étudiant ne correspond à ce matricule.',
            'binome_matricule.not_in' => 'Vous ne pouvez pas vous désigner comme binôme.',
            'fichier_pdf.uploaded' => 'Le téléversement a échoué : le fichier est trop volumineux ou interrompu.',
            'annee_academique_id.unique' => "Vous avez déjà un mémoire pour cette année académique. Seul un mémoire rejeté peut être redéposé.",
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if (! $this->filled('annee_academique_id')) {
                return;
            }

            $regle = app(RegleDepotService::class);
            $annee = (int) $this->input('annee_academique_id');

            if ($regle->aDejaUnMemoire($this->user(), $annee)) {
                $v->errors()->add('annee_academique_id', 'Vous avez déjà un mémoire pour cette année académique.');
            }

            if ($this->filled('binome_matricule') && ! $v->errors()->has('binome_matricule')) {
                $binome = User::where('matricule', $this->input('binome_matricule'))->first();

                if ($binome && $regle->aDejaUnMemoire($binome, $annee)) {
                    $v->errors()->add('binome_matricule', 'Ce binôme est déjà auteur d’un mémoire pour cette année académique.');
                }
            }
        });
    }

}