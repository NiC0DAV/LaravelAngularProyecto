<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-orm', '\App\Http\Controllers\pruebasController@testORM');

//RUTAS DEL API-REST-FUL

    /*Metodos HTTP comunes

    GET: Para conseguir datos o recursos
    POST: Para guardar datos, recursos o hacer un proceso logico con una respuesta
    PUT: Actualizar recursos o datos
    DELETE: Eliminar datos o recursos

    */

    //Rutas de prueba
    Route::get('/user/pruebas', '\App\Http\Controllers\userController@pruebas');

    Route::get('/categories/pruebas', '\App\Http\Controllers\categoryController@pruebas');

    Route::get('/posts/pruebas', '\App\Http\Controllers\postController@pruebas');

    //Rutas del controlador de usuarios
    Route::post('/api/register', 'App\Http\Controllers\userController@register');
    Route::post('/api/login', 'App\Http\Controllers\userController@login');
    Route::put('/api/user/update', 'App\Http\Controllers\userController@update');
    Route::post('/api/user/upload', 'App\Http\Controllers\userController@upload');


