<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{

    private $assign;

    public function login ()
    {
        return view('usuario_login');
    }
    
    public function do_login (Request $request)
    {
        // validaçoes
        $valid = $request->validate([
            'email'  => 'email|required|max:100',
            'senha'  => 'required|max:100'
        ],[
            'email.email'     => 'O Campo e-mail deve conter um e-mail válido',
            'email.required'  => 'O Campo E-mail é obrigatório',
            'email.max'       => 'O Campo E-mail deve ter no máximo 100 caracteres',
            'senha.required'  => 'Senha é obrigatória',
            'senha.max'       => 'Senha deve conter no máximo 100 caracteres'
        ]);

        // resgatando e descriptografando a senha do banco, através do e-mail de login
        $user = UsuarioModel::getSenha($request->email);
        if (!$user) {
            return response()->json(['error' => true, 'msg' => 'Usuário não encontrado.']);
        }
        
        // descriptografando a senha do banco
        $this->assign = Hash::check($request->senha, $user->senha);
        
        // comparando as senhas
        if (!$this->assign) {
            return response()->json(['error' => true, 'msg' => 'Usuário não encontrado']);
        }

        session()->put('usuario', $user->id);

    }

    public function dashboard ()
    {
        $resultSet = UsuarioModel::where('id','<>','NULL')->select('nome','email','id')->get();
        return view('usuario_dashboard', compact('resultSet'));
    }

    public function showUsuario ($id) 
    {
        $resultSet = UsuarioModel::where('id','=',Crypt::decrypt($id))->select('nome','email','id')->first();

        if ($resultSet) {
            $resultSet->id = $id;
            return response()->json(['error' => false, 'resultSet' => $resultSet, 'msg' => 'Registro encontrado']);
        }

        return response()->json(['error' => true, 'msg' => 'Erro ao buscar registro']);
    }

    public function createUsuario (Request $request)
    {
        // validaçoes
        $valid = $request->validate([
            'nome'   => 'required',
            'email'  => 'email|required|max:100|unique:usuarios',
            'senha'  => 'required|max:100|min:6'
        ],[
            'nome.required'   => 'O Campo Nome é obrigatório',
            'email.email'     => 'O Campo e-mail deve conter um e-mail válido',
            'email.required'  => 'O Campo E-mail é obrigatório',
            'email.max'       => 'O Campo E-mail deve ter no máximo 100 caracteres',
            'email.unique'    => 'Já existe usuário com esse E-mail',
            'senha.required'  => 'Senha é obrigatória',
            'senha.max'       => 'Senha deve conter no máximo 100 caracteres',
            'senha.min'       => 'Senha deve conter ao menos 6 caracteres'
        ]);

        $registro = $request->all();
        $registro['senha'] = Hash::make($registro['senha']);

        DB::beginTransaction();

        try {
            UsuarioModel::create($registro);
            DB::commit();
            return response()->json(['error' => false, 'msg' => 'Cadastrado com Sucesso.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'msg' => 'Houve um erro ao cadastrar no Banco']);
        }
    }


    public function updateUsuario (Request $request)
    {
        // validaçoes
        $valid = $request->validate([
            'nome'      => 'required',
            'email'     => 'email|required|max:100',
            'email_old' => 'required|email',
            'senha'     => 'nullable',
            'id_edit'   => 'required'
        ],[
            'nome.required'      => 'O Campo Nome é obrigatório',
            'email.email'        => 'O Campo e-mail deve conter um e-mail válido',
            'email.required'     => 'O Campo E-mail é obrigatório',
            'email.max'          => 'O Campo E-mail deve ter no máximo 100 caracteres',
            'email_old.required' => 'O Campo E-mail de verificação é obrigatório',
            'email_old.email'    => 'O Campo E-mail de verificação deve ser um E-mail Válido',
            'senha.max'          => 'Senha deve conter no máximo 100 caracteres',
            'id_edit.required'   => 'Identificação do id é necessário'
        ]);

        // pegando o id do usuario e descriptografando
        $id_usuario = self::getIdUsuario($request->id_edit);
        
        $registro = $request->all();
        // tirando do array o token e o metodo
        unset($registro['_token']);
        unset($registro['_method']);
        // se a senha for diferente de vazio
        if ($registro['senha'] != "") {
            $registro['senha'] = Hash::make($registro['senha']);
        } else {
            unset($registro['senha']);
        }
        

        // se o e-mail mudou, tem que se ver se o novo e-mail já não está cadastrado no sistema
        if ($request->email != $request->email_old) {
            $jaExiste = UsuarioModel::where('email','=',$request->email)->first();
            if ($jaExiste) {
                return response()->json(['error' => true, 'msg' => 'O E-mail já se encontra cadastrado no Sistema']);
            }
        }

        // retirando o email antigo da query
        unset($registro['email_old']);
        // retirando o id_edit
        unset($registro['id_edit']);


        // Seguindo com a atualização
        DB::beginTransaction();

        try {
            UsuarioModel::where('id','=',$id_usuario)->update($registro);
            DB::commit();
            return response()->json(['error' => false, 'msg' => 'Editado com Sucesso.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }



    public function deleteUsuario (string $id) {
        
        $id_usuario = self::getIdUsuario($id);

        if ($id_usuario) {

            DB::beginTransaction();

            try {
                UsuarioModel::where('id','=',$id_usuario)->delete();
                DB::commit();
                return response()->json(['error' => false, 'msg' => 'Deletado com Sucesso.']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => true, 'msg' => $e->getMessage()]);
            }

        }
    }



    private static function getIdUsuario(string $hash) : string 
    {
        return Crypt::decrypt($hash);
    }


}
