<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods'; 
    use HasFactory;
    protected $fillable = [
        'title',
        'img',
        'desc',
        'price',
    ];
    public function userRoomFood(){
        return $this->hasMany("App\Models\UserRoomFood","food_id");
    }            
}
