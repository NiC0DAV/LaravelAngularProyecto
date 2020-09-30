<?php

namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

Class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = 461958;
    }
    
    public function signUp($email, $password, $getToken = null){//primero crear service provider desde el cmd php artisan make:provider [nombreServiceProvider]
        //Buscar si existe el usuario con su credencial de logeo
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //Si las hay comprobar si son correctas
        $signUp = false;

        if(is_object($user)){
            $signUp = true;
        }
        //Generar token con los datos del usuario identificado

        if ($signUp){
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),//Fecha de creacion del token
                'exp' => time()+(7*24*60*60)//tiempo de expiracion del token
            );

            $jwt = JWT::encode($token, $this->key, 'HS256' );//3 parametros los datos a recibir el token
                                                           //la clave super secreta y el metodo de cifrado
            $decodeJwt = JWT::decode($jwt, $this->key, ['HS256']);
            //Devolver los datos decodificados o el token en funcion de un parametro
            if(is_null($getToken)){
                $data = $jwt;
            }else{
                $data = $decodeJwt;
            }

        }else{
            $data = array(
                'status' => 'Error',
                'message' => 'Intento de logeo incorrecto.'
            );
            
        }
        return $data;

        // return 'Metodo de la clase JWTAUTH';
    }
    
}