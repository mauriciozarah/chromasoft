<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'email',
        'senha'
    ];
 

    public static function getSenha (string $email) : mixed 
    {
        $res = UsuarioModel::where('email','=',$email)->select('id', 'senha')->first();

        if ($res) {
            $std = new \stdClass();
            $std->id = $res->id;
            $std->senha = $res->senha;
            return $std;
        }

        return false;
    }
}
