<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        $find = strpos($this->route()->action["controller"], '@');
        $method = substr($this->route()->action["controller"], $find + 1);
        return match ($method) {
            'register' => [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|string|min:8',
            ],
            'login' => [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]
        };
    }
}
