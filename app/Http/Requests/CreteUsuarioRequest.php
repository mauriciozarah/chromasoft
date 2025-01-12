<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreteUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         *  $user = auth()->user();
         *  return $user->can('create', model)
         */

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
            'nome'   => 'required',
            'email'  => 'email|required|max:100|unique:usuarios',
            'senha'  => 'required|max:100|min:6'
        ];
    }

    public function messages ()
    {
        return [
            'required' => 'O Campo :attribute é obrigatório',
            'email'    => 'O Campo :attribute deve conter um e-mail válido',
            'max:100'  => 'O Campo :attribute deve ter no máximo 100 caracteres',
            'unique'   => 'Já existe usuário com esse :attribute',
            'min:6'    => ':attribute deve conter ao menos 6 caracteres'
        ];
    }
}
