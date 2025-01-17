<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreteUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Requests\DoLoginRequest;

class UsuarioController extends Controller
{

    private $assign;

    public function login ()
    {
        return view('usuario_login');
    }
    
    public function do_login (DoLoginRequest $request)
    {
 
        // resgatando o usuario
        $user = UsuarioModel::getSenha($request->email);
        // se não existir
        if (!$user) {
            return response()->json(['error' => true, 'msg' => 'Usuário não encontrado.']);
        }
        
        // comparando as senhas
        $this->assign = Hash::check($request->senha, $user->senha);
        
        // se senhas forem diferentes
        if (!$this->assign) {
            return response()->json(['error' => true, 'msg' => 'Usuário não encontrado']);
        }

        // se as senhas e o login for feito com sucesso
        session()->put('usuario', $user->id);

    }

    public function dashboard ()
    {
        $resultSet = UsuarioModel::where('id','<>','NULL')->select('nome','email','id')->get();
        
        return view('usuario_dashboard', compact('resultSet'));
    }


    // --------------   Funções do CRUD de Usuarios ----------------------------------


    public function showUsuario ($id) 
    {
        $resultSet = UsuarioModel::where('id','=',self::getIdUsuario($id))->select('nome','email','id')->first();

        if ($resultSet) {
            $resultSet->id = $id;
            return response()->json(['error' => false, 'resultSet' => $resultSet, 'msg' => 'Registro encontrado']);
        }

        return response()->json(['error' => true, 'msg' => 'Erro ao buscar registro']);
    }


    public function createUsuario (CreteUsuarioRequest $request)
    {
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


    public function updateUsuario (UpdateUsuarioRequest $request)
    {
 
        // pegando o id do usuario e descriptografando
        $id_usuario = self::getIdUsuario($request->id_edit);
     
        // tirando o token e o method do request e o id da edição
        $registro = $request->except('_method','id_edit');


        // =============== TRATAMENTO DE DADOS ==========================
        $registro = self::senhaUpdate($registro);                     //|
        $registro = self::emailUpdateExists($registro);               //|
        // =============== FIM DE TRATAMENTO DE DADOS ===================


        // se passou pelo tratamento de dados
        if ($registro) {

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

        // se não passou pelo tratamento de dados
        if (!$registro) {
            return response()->json(['error' => true, 'msg' => 'E-mail já cadastrado']);
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




    // ---------  Funções de tratativas das operações -------------------------------

    private static function getIdUsuario (string $hash) : string 
    {
        return Crypt::decrypt($hash);
    }

    private static function senhaUpdate (array $registro) : array 
    {
        // se a senha for diferente de vazio
        if ($registro['senha'] != "") {
            // criptografando a senha
            $registro['senha'] = Hash::make($registro['senha']);
        } else {
            // retirando a senha do array de atualização
            unset($registro['senha']);
        }

        return $registro;
    }

    private static function emailUpdateExists (array $registro) : mixed 
    {
        $jaExiste = false;

        // se o e-mail mudou, tem que se ver se o novo e-mail já não está cadastrado no sistema
        if ($registro['email'] != $registro['email_old']) {
            $jaExiste = UsuarioModel::where('email','=',$registro['email'])->first();
        }

        // retirando o email antigo do array de atualização
        unset($registro['email_old']);

        if ($jaExiste) {
            return false;
        }

        return $registro;
    }

    // ------------  fim das funções de tratativas -----------------------------------------------

}
