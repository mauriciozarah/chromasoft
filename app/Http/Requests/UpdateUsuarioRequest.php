<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize () : bool
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
            'nome'      => 'required',
            'email'     => 'email|required|max:100',
            'email_old' => 'required|email',
            'senha'     => 'nullable|min:6',
            'id_edit'   => 'required'
        ];
    }

    public function messages () : array
    {
        return [
            'required'      => 'O Campo :attribute é obrigatório',
            'email'        => 'O Campo :attribute deve conter um e-mail válido',
            'max:100'          => 'O Campo :attribute deve ter no máximo 100 caracteres',
            'min:6'          => ':attribute deve conter no mínimo 6 caracteres'
        ];
    }
}
