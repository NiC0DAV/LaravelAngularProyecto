<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userController extends Controller
{
    public function pruebas(Request $request){
        return "Accion de pruebas de userController";
    }

    public function register(Request $request){
        $name = $request->input('name');
        $surname = $request->input('surname');
        return "Accion de registro de usuario $name $surname";
    }

    public function login(Request $request){
        return "Accion de logeo de usuario";
    }
}
