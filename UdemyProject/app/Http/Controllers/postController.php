<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Helpers\JwtAuth;

class postController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index', 'show']]); 
    }

    public function index(){
        $post = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $post
        ],200);
    }

    public function show($id){
        $post = Post::find($id)->load('category');

        if(is_object($post)){
            
            $data = [
            'code' => 200,
            'status' => 'success',
            'posts' => $post
            ];

        }else{
            
            $data = [
            'code' => 404,
            'status' => 'Error',
            'message' => 'The entered data does not exists'
            ];

        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        //Recoger datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //Conseguir usuario indentidicado 
        if (!empty($params_array)){
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            
            $user = $jwtAuth->checkToken($token, true); 
            
            //Validar datos
            $validate = \Validator::make($params_array,[
                'title' =>  ['required'],
                'content' => ['required'],
                'category_id' => ['required'],
                'image' => ['required']
            ]);
            
            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'The post could not be saved, missing data'
                ];
            }else{
                //Guardar Articulo
                $post = new Post();

                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;

                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'Success',
                    'post' => $post
                ];
            }

        }else{
            $data = [
                'code' => 400,
                'status' => 'Error',
                'message' => 'Make sure to send the data correctly'
            ];
        }
        //Devolver respuesta

        return response()->json($data, $data['code']);
    }
}
