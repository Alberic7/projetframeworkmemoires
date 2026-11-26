<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejetMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La Gate 'valider' est vérifiée dans le contrôleur
    }

    public function rules(): array
    {
        return [
            'motif' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'motif.required' => 'Vous devez préciser un motif de rejet.',
            'motif.min' => 'Le motif doit comporter au moins 10 caractères.',
        ];
    }
}
