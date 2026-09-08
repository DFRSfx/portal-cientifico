<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StatisticsServicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "authorId" => "nullable|exists:authors,id",
            'startYear' => 'nullable|required_with:endYear|integer',
            "endYear" => 'nullable|required_with:startYear|integer',
            'statsType' => [
                "required",
                Rule::in(['global', 'type']),
            ]
        ];
    }
}
