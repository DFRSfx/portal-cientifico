<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OutputsFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //$userIsValid = session("user") !== null;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "title" => "nullable|string",
            "category" => "nullable|array",
            "keywords"=> "nullable|array",
            "citationNames"=> "nullable|array",
            "startYear" => "nullable|required_with:endYear|integer|numeric|digits:4",
            "endYear" => "nullable|required_with:startYear|integer|numeric|digits:4",
            "status"=> "nullable|array|max:2",
            "status.*" => [
                "required_with:status",
                Rule::in(["Published","Accepted","In review", "Submitted", "In print"])
                ]
        ];
    }
}
