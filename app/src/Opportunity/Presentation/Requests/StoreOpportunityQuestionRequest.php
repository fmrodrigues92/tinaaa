<?php

namespace App\Src\Opportunity\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpportunityQuestionRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:5000'],
            'context' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
