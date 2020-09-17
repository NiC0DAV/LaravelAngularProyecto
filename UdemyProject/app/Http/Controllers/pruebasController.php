<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;


class pruebasController extends Controller
{
    
    public function testORM(){

        $posts = Post::all();
        var_dump($posts);

        die();
    }
}
