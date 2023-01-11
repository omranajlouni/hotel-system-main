<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'status_id ',
        'duration',
        'start_date',
        'end_date',
        
    ];
    public function userRoomService(){
        return $this->hasMany("App\Models\UserRoomService","user_room_id");
    }
    public function userRoomFood(){
        return $this->hasMany("App\Models\UserRoomFood","users_room_id");
    }        
    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }                    
    public function status(){
        return $this->belongsTo("App\Models\Status","status_id");
    }                    
    public function room(){
        return $this->belongsTo("App\Models\Room","room_id");
    }                              
}
