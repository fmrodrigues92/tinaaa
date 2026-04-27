<?php

namespace App\Src\ProfessionalProfile\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfessionalExperienceRequest extends FormRequest
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
            'role' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'started_on' => ['nullable', 'date'],
            'ended_on' => ['nullable', 'date', 'after_or_equal:started_on'],
            'is_current' => ['nullable', 'boolean'],
            'context' => ['nullable', 'string', 'max:5000'],
            'responsibilities' => ['nullable', 'string', 'max:5000'],
            'results' => ['nullable', 'string', 'max:5000'],
            'technologies' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
