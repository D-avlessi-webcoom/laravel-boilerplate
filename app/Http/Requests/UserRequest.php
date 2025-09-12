<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:191',
            'email' => 'string|max:191|unique:users,email',
            'phone' => 'string|max:191|unique:users,phone',
            'username' => 'string|max:191|unique:users,username',
            'email_verified_at' => 'date',
            'password' => 'string|max:191',
            'remember_token' => 'string|max:100',
        ];
    }
}
