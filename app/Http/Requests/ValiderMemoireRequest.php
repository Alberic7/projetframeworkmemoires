<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValiderMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telechargement_autorise' => ['nullable', 'boolean'],
            'acces_restreint' => ['nullable', 'boolean'],
            'date_embargo' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date_embargo' => "date d'embargo",
        ];
    }

    public function messages(): array
    {
        return [
            'date_embargo.after' => "La date d'embargo doit être postérieure à aujourd'hui.",
        ];
    }
}