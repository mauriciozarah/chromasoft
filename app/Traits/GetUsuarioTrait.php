<?php 

namespace App\Traits;

trait GetUsuarioTrait
{
    public static function getId () : int 
    {
        return session('usuario')['id'];
    }
}