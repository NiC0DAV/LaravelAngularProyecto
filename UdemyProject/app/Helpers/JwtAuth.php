<?php

namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

Class JwtAuth{
    
    public function signUp(){//primero crear service provider desde el cmd php artisan make:provider [nombreServiceProvider]
        //Buscar si existe el usuario con su credencial de logeo
        //Si las hay comprobar si son correctas
        //Generar token con los datos del usuario identificado
        //Devolver los datos decodificados o el token en funcion de un parametro

        return 'Metodo de la clase JWTAUTH';
    }
    
}