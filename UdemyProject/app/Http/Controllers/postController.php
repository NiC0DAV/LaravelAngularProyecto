<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Helpers\JwtAuth;

class postController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index', 
                                                    'show', 
                                                    'getImage', 
                                                    'getPostsByCategory', 
                                                    'getPostsByUser']]); 
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
            $user = $this->getIdentity($request);   
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

    public function update($id, Request $request){
        //Recoger datos
        $json = $request->input('json', null);  //$request es para recoger parametros por POST
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
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
                    'message' => 'There was an error validating de data'
                ];
            }else{
                //Eliminar lo que no se quiere actualizar
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                //Obtener usuario identificado
                $user = $this->getIdentity($request);   
                
                //Buscar el registro
                $post = Post::where('id', $id)
                            ->where('user_id', $user->sub)
                            ->first();

                if(!empty($post) && is_object($post)){
                    //Actualizar el registro en concreto
                    $post->update($params_array);
                    
                    //Devolver respuesta
                    $data = [
                        'code' => 200,
                        'status' => 'Success',
                        'post' => $post,
                        'changes'=>$params_array
                    ];
                }else{
                    $data = [
                        'code' => 400,
                        'status' => 'Error',
                        'message'=> 'You are not the owner of the post that you are trying to update'
                    ];
                }
                // $where = [
                //     'id' => $id,
                //     'user_id' => $user->sub
                // ];
                // $post = Post::updateOrCreate($where, $params_array);

                
            }

        }else{
            $data = [
                'code' => 400,
                'status' => 'Error',
                'message' => 'Incorrect Data'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        // Conseguir usuario identificado
        $user = $this->getIdentity($request);

        // Comprobar si existe el registro
        $post = Post::where('id', $id)
                        ->where('user_id', $user->sub)
                        ->first();

        if(!empty($post)){
                    //Borrarlo
        $post->delete();

        //Devolver una respuesta
        $data=[
            'code' => 200,
            'status' => 'success',
            'post' => $post
        ];
        }else{
            $data=[
                'code' => 400,
                'status' => 'Error',
                'message' => 'The post to be deleted does not exists'
            ];
        }


        return response()->json($data, $data['code']);
    }   

    private function getIdentity($request){
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true); 

        return $user;
    }

    public function upload(Request $request){
        // Recoger la imagen de la peticion
         $image = $request->file('file0');

        // Validar Imgen
        $validate = \Validator::make($request->all(),[
            'file0' => ['required', 'image', 'mimes:jpg,jpeg,png,gif']
        ]);
        
        // Guardar imagen 
        if(!$image || $validate->fails()){
            $data=[
                'code' => 400,
                'status' => 'Error',
                'message' => 'Error uploading the image'
            ];
        }else{
            $imageName = time().$image->getClientOriginalName();

            \Storage::disk('images')->put($imageName, \File::get($image));
            $data=[
                'code' => 200,
                'status' => 'Success',
                'image' => $imageName
            ];
        }

        // Devolver Datos
        return response()->json($data, $data['code']);
    }

    public function getImage($fileName){
        // Comprobar si existe el fichero
        $isset = \Storage::disk('images')->exists($fileName);

        if($isset){
            // Conseguir la imagen
            $file = \Storage::disk('images')->get($fileName);

            // Devolver la imagen 
            return new Response($file, 200);
        }else{
            $data = [
                'code' => 404,
                'Status' => 'Error',
                'Message' => 'The image does not exists'
            ];
        }

        // Mostrar el Error
        return response()->json($data, $data['code']);
        
    }

    public function getPostsByCategory($id){
        $post = Post::where('category_id', $id)->get();

        return response()->json([
            'status' => 'Success',
            'posts' => $post
        ],200);
    }

    public function getPostsByUser($id){
        $post = Post::where('user_id', $id)->get();

        return response()->json([
            'status' => 'Success',
            'posts' => $post
        ],200);
    }

}
