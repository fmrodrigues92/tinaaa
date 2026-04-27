<?php

namespace App\Src\ProfessionalProfile\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfessionalProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'professional_experience_id' => ['nullable', 'integer', 'exists:professional_experiences,id'],
            'name' => ['required', 'string', 'max:255'],
            'problem' => ['nullable', 'string', 'max:5000'],
            'solution' => ['nullable', 'string', 'max:5000'],
            'impact' => ['nullable', 'string', 'max:5000'],
            'technical_decisions' => ['nullable', 'string', 'max:5000'],
            'technologies' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
