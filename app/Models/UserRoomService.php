<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserNotification;

class UserRoomService extends Model
{
    protected $table = 'user_room_services'; 

    use HasFactory;
    protected $fillable = [
        'user_room_id',
        'service_id',
        'status_id',
        'notes',
    ];
    public function status(){
        return $this->belongsTo("App\Models\Status","status_id");
    }
    public function userRoom(){
        return $this->belongsTo("App\Models\UserRoom","user_room_id");
    }
    public function service(){
        return $this->belongsTo("App\Models\Service","service_id");
    }            

    public static function boot()
    {
        parent::boot();

        static::updated(function($model)
        {
            $user_room_ser = UserRoomService::where('user_room_id',$model->user_room_id)->first();
           // dd($user_ser);
                $urs_st_id = $user_room_ser->status_id;
                $noti = new UserNotification();
                $user_room = userRoom::where('id',$model->user_room_id)->first();
                $ur_user_id = $user_room->user_id;
                if($urs_st_id==5){
                        $noti->notification_id=3;
                }
                else if($urs_st_id == 6)
                {
                    $noti->notification_id=4;
                }

                else if($urs_st_id == 7)
                {
                    $noti->notification_id=2;
                }
                $noti->user_id = $ur_user_id;
                $noti->status_id=4;
                $noti->save();
        });
    }


}
