<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejetMemoireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motif' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'motif.required' => 'Le motif du rejet est obligatoire.',
            'motif.min' => 'Le motif doit contenir au moins 10 caractères pour que l\'étudiant comprenne le rejet.',
        ];
    }
}