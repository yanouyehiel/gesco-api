<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class RegisterRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', 'string', Rule::unique(User::class, 'email')],
            'password' => 'required|string|min:8',
            'telephone' => 'required|string',
            'matricule' => 'required|string',
            'prenom' => 'required|string',
            'role_id' => 'required',
            'ecole_id' => 'required',
        ];
    }
}
