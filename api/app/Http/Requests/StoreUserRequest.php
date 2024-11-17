<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_user_type' => 'required|exists:user_type,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'document_type' => ['required', Rule::in(['cpf', 'cnpj'])],
            'document' => 'required|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'business' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['A', 'I'])],
        ];
    }
}
