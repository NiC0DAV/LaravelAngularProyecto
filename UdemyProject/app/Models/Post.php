<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    //Relacion de muchos a uno
    public function user(){
        return $this->belongsTo('App/User', 'user_id');//Trae objetos de  usuario relacionados por el user id
    }

    public function category(){
        return $this->belongsTo('App/Category', 'category_id');//Trae el objeto relacionado con el id de la categoria
    }

}
