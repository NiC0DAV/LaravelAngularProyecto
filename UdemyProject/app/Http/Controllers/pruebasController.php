<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;


class pruebasController extends Controller
{
    
    public function testORM(){

        $users = User::all();
        $posts = Post::all();
        foreach($posts as $post){
            // dd($post->user);
            echo "<h1>".$post->title."</h1>";
            echo "<span style='color:gray;'>{$post->user->name} - {$post->category->name}</span>";
            echo "<p>".$post->content."</p>";
            echo "<hr>";
        }
        die();
    }
}
