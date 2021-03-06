<?php

use Illuminate\Support\Facades\Route;

//Cargar Clases
use App\Http\Middleware\ApiAuthMiddleware;

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
    Route::post('/api/user/upload', 'App\Http\Controllers\userController@upload')->middleware(App\Http\Middleware\ApiAuthMiddleware::class);
    Route::get('/api/user/avatar/{filename}', 'App\Http\Controllers\userController@getImage');
    Route::get('/api/user/detail/{id}', 'App\Http\Controllers\userController@detail');

    //Rutas del controlador de categorias
    Route::resource('/api/category', 'App\Http\Controllers\categoryController');//En cmd php artisan route:list gracias al resource ya nos dice el nombre de los metodos

    //Rutas del controlador de entradas (posts)
    Route::resource('api/post', 'App\Http\Controllers\postController');
    Route::post('/api/post/upload', 'App\Http\Controllers\postController@upload');
    Route::get('/api/post/image/{filename}', 'App\Http\Controllers\postController@getImage');
    Route::get('/api/post/category/{id}', 'App\Http\Controllers\postController@getPostsByCategory');
    Route::get('/api/post/user/{id}', 'App\Http\Controllers\postController@getPostsByUser');