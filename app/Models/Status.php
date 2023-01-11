<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'person_num',
        'desc',

    ];
    public function userNotification(){
        return $this->hasMany("App\Models\UserNotification","status_id");
    }
    public function userRoom(){
        return $this->hasMany("App\Models\UserRoom","status_id");
    }
    public function userRoomFood(){
        return $this->hasMany("App\Models\UserRoomFood","status_id");
    }
    public function userRoomService(){
        return $this->hasMany("App\Models\UserRoomService","status_id");
    }        
}
