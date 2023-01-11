<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRoom;
use App\Models\User;
use App\Models\UserNotification;
use App\Helpers\APIHelpers;
use DateTime;

class Reserve_controller extends Controller
{

    public function form()
    {
        //here show the form

    }

    
     
    public function show()
    {
        $reservs = UserRoom::all();
        $response= APIHelpers::createAPIResponse(false,200,'here is all the reservations',$reservs,1);
        return response()->json($response,200);
    }



    public function store(Request $request)
    {
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;
        $status_id=9;
       
        $user_room= UserRoom::create([
            "user_id"=>$user_id,
            "room_id"=>$request->room_id,
            "status_id"=>$status_id,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ]);
        
        //$user_room->status_id = 9; // bending
    
        //derive the duration (end date - start date)
        $days = UserRoom::where('id',$user_room->id)->firstOrFail();
        $d1 =  new DateTime($days->start_date);
        $d2 = new DateTime($days->end_date);
        $interval = $d1->diff($d2);
        $user_room->duration = $interval->d; //y:m:d:h:m:s
        
        $user_room->save();

        $response= APIHelpers::createAPIResponse(false,201,'the reserve added',$user_room,1);
        return response()->json($response,201);

         /*$room = UserRoom::create([
            'user_id'=>$user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

         //derive the duration (end date - start date)
         $days = UserRoom::where('user_id',$user->id)->get(['start_date','end_date'])->firstOrFail();
         $d1 =  new DateTime($days->start_date);
         $d2 = new DateTime($days->end_date);
         $interval = $d1->diff($d2);
        $room->duration = $interval->d;
        $room->status_id = 2;
        $room->save();
        */
 
    }
 

    public function accept(Request $request)
    {
        //accept the request 
        $data1 = UserRoom::create([
            "user_id"=>$request->user_id,
            "room_id"=>$request->room_id,
            "status_id"=>$request->status_id,
            "duration"=>$request->duration,
        ]); 
        $response= APIHelpers::createAPIResponse(false,201,'',$data1,1);
        return response()->json($response,201);

    }



    public function accept_extend(Request $request)
    {
        //accept the request and send Notification to user
        $data1=UserRoom::create([
            "user_id"=>$request->user_id,
            "room_id"=>$request->room_id,
            "status_id"=>$request->status_id,
            "duration"=>$request->duration,
        ]); 

        $data2=UserNotification::create([
            "notification_id"=>$request->notification_id,
            "user_id"=>$request->user_id,
            "status_id"=>$request->status_id,
        ]); 

        $response= APIHelpers::createAPIResponse(false,201,'',$data1+$data2,1);
        return response()->json($response,200);

    }
}
