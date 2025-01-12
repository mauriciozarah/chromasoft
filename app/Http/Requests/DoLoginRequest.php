<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoLoginRequest extends FormRequest
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
    public function rules () : array
    {
        return [
            'email'  => 'email|required|max:100',
            'senha'  => 'required|max:100'
        ];
    }

    public function messages () : array 
    {
        return [
            'email'     => "O Campo :attribute deve conter um e-mail válido",
            'required'  => 'O Campo :attribute é obrigatório',
            'max:100'       => 'O Campo :attribute deve ter no máximo 100 caracteres'
        ];
    }
}
