<?php

namespace App\Src\Opportunity\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOpportunityRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'seniority' => ['nullable', 'string', 'max:255'],
            'offered_salary' => ['nullable', 'string', 'max:255'],
            'expected_salary' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'active', 'archived'])],
            'description' => ['nullable', 'string', 'max:20000'],
            'requirements' => ['nullable', 'string', 'max:20000'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
