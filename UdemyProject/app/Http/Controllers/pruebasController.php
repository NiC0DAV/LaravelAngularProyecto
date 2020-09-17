<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;


class pruebasController extends Controller
{
    
    public function testORM(){

        $posts = Post::all();
        var_dump($posts);

        die();
    }
}
