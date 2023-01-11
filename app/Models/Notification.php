<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'desc',
        'img',
     ];
    public function userNotification(){
        return $this->hasMany("App\Models\UserNotification","notification_id");
    }
}
