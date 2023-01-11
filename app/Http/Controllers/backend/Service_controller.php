<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Helpers\APIHelpers;
use App\Models\UserNotification;
use App\Models\UserRoomService;
use App\Models\User;
use App\Models\UserRoom;

class Service_controller extends Controller
{
    
    public function show_service(Request $request)
    {
        $service = Service::where('id', $request->id)->get(['price'])->firstOrFail();
        $price=$service->price;
       
        //$response= APIHelpers::createAPIResponse(false,200,'here is the service',$service);
        return response($price);
    }


    public function add_service_request(Request $request)
    {
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;
        
        $userroom = UserRoom::where('user_id',$user_id)->where('status_id',2)->firstOrFail();
        $user_room_id=$userroom->id;
      
        $user_room_service= UserRoomService::create([
            "user_room_id"=>$user_room_id,
            "service_id"=>$request->service_id,
            "status_id"=>4,
            "notes"=>$request->notes,
        ]);
       
        
        $response= APIHelpers::createAPIResponse(false,201,'service added',$user_room_service,1);
        return response()->json($response,200);
    }


    public function index($id)
    {
        $user_room_service= UserRoomService::find($id);

        if (is_null($user_room_service)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$user_room_service,1);
            return response()->json($response,404);
        }

        $response= APIHelpers::createAPIResponse(false,200,'here is service request',$user_room_service,1);
        return response()->json($response,200);
    }


    public function show()
    {
        $service = UserRoomService::all();
        $response= APIHelpers::createAPIResponse(false,200,'here is all services',$service,1);
        return response()->json($response,200);
    }


    public function show_user_room_services(Request $request)
    {
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;
        $user_room = UserRoom::where('user_id',$user_id)->where('status_id',2)->first();
        //return $user_room;
        if($user_room == NULL)
        {
            //$account_status =0;
            $response= APIHelpers::createAPIResponse(false,400,'you have not checked_in',$user_room,0);
            return response()->json($response,400);
      
        }
        
        $user_room_id = $user_room->id;
       // $service=$account_status =1;
       
        $service = UserRoomService::where('user_room_id', $user_room_id)->get();
        $response= APIHelpers::createAPIResponse(false,200,'here is user services requests',$service,0);
        return response()->json($response,200);
    }


    public function accept(Request $request)
    {
        
        $service= UserRoomService::find($request->id);
        
        if (is_null($service)){
            $response= APIHelpers::createAPIResponse(true,404,'service not found',$service,1);
            return response()->json($response,404);
        }
        $data1=UserNotification::create([
            "notification_id"=>$request->notification_id,
            "user_id"=>$request->user_id,
            "status_id"=>$request->status_id,
        ]); 
        $response= APIHelpers::createAPIResponse(false,200,'service has been accepted',$data1,1);
        return response()->json($response,200);
    }


    public function decline(Request $request)
    {
        $data1=UserNotification::create([
            "notification_id"=>$request->notification_id,
            "user_id"=>$request->user_id,
            "status_id"=>$request->status_id,
        ]); 
        if (is_null($data1)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$data1,1);
            return response()->json($response,404);
        }
        $response= APIHelpers::createAPIResponse(false,200,'service has been declined',$data1,1);
        return response()->json($response,200);
    }

}
