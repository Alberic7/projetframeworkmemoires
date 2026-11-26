<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValiderMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La Gate 'valider' est vérifiée dans le contrôleur
    }

    public function rules(): array
    {
        return [
            'telechargement_autorise' => ['sometimes', 'boolean'],
            'acces_restreint' => ['sometimes', 'boolean'],
            'date_embargo' => ['nullable', 'date', 'after:today'],
        ];
    }
}
