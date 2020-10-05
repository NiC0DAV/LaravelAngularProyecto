<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class userController extends Controller
{
    public function pruebas(Request $request){
        return "Accion de pruebas de userController";
    }



    public function register(Request $request){

        //Recoger los datos del usuario por post

        $json = $request->input('json', null);//Se recibe el json con los datos
        $params = json_decode($json); //Me genera un Objeto del json
        $params_array = json_decode($json, true); //Me genera un array del json

        // var_dump($params->name);
        // die();



        //Validar esos datos
        if(!empty($params_array) && !empty($params)){

            $validate = \Validator::make($params_array,[//Se especifican las reglas de validacion del validator
                'name' => ['required','alpha'],
                'surname' => ['required', 'alpha'],
                'email' => ['required', 'email', 'unique:users'],//Validar existencia del usuario
                'password' => ['required']
            ]);

            if($validate->fails()){//Se crea la condicional segun el resultado de la validación
                //Validacion Fallida
                $data = array(
                    'status' => 'Error',
                    'code' => 400,
                    'message' => 'El usuario no se ha creado correctamente',
                    'errors' => $validate->errors()
                );
            }else{

                //Validacion pasada Correctamente
                //Cifrar Contraseña
                // $hashPass=password_hash($params->password, PASSWORD_BCRYPT, ['cost' => 4]);//Proceso del cifrado de la contraseña, el cost define cuantas veces se cifraa si misma
                $hashPass = hash('sha256', $params->password);
                //Crear usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $hashPass;
                $user->role = 'ROLE_USER';

                // dd($user);

                //Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'Success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        }else{
            $data = array(
                'status' => 'Error',
                'code' => 400,
                'message' => 'Los datos enviados no son correctos'
            );
        }


        //Limpiar los datos Ingresados

        $params_array = array_map('trim', $params_array);//Se le eliminan los espacios en blanco al arreglo y ademas se eliminan caracteres especiales



        //Si se crea correctamente enviar mensaje

        return response()->json($data, $data['code']);
    }


    

    public function login(Request $request){
        $jwtAuth = new \JwtAuth();

        //Recibir el POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array= json_decode($json, true);

        //Validar datos
        $validate = \Validator::make($params_array,[
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        
        if($validate->fails()){
            $signUp = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        }else{
            //Cifrar Contraseña
            $hashPass = hash('sha256', $params->password);

            //Devolver Token o datos
            $signUp = $jwtAuth->signUp($params->email, $hashPass);

            if(!empty($params->getToken)){
                $signUp = $jwtAuth->signUp($params->email, $hashPass, true); 
            }
        }
        // dd($hashPass);die;
        // return $jwtAuth->signUp($email, $hashPass);
        return response()->json($signUp, 200);
    }
}
