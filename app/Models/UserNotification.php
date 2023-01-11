<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_id',
        'user_id',
        'status_id',
    ];
    public function notification(){
        return $this->belongsTo("App\Models\Notification","notification_id");
    }            
    public function user(){
        return $this->belongsTo("App\Models\User","user_id");
    }            
    public function status(){
        return $this->belongsTo("App\Models\Status","status_id");
    }                    
}
