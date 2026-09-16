<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserCreationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            "ciencia_vitae" => ["exclude_if:type,administrative", "required", "regex:/^([A-Z0-9]{4}-){2}[A-Z0-9]{4}$/i", 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            "type" => ["string", Rule::in(["student", "teacher", "researcher", "administrative"])],
            "is_isla" => ['required','boolean']
        ];
    }
}
