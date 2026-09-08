<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorSearchRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "nullable|string",
            "domainActivity" => "nullable|array",
            "keywords"=> "nullable|array",
            "language" => "nullable|array",
            "domainActivity.*" => "required_with:domainActivity|int",
            "language.*" => "required_with:language|int",
            "keywords.*" => "required_with:keywords|int"
            
        ];
    }
}
