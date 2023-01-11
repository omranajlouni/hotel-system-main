<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Helpers\APIHelpers;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\User;
use App\Models\UserRoom;
use App\Models\UserRoomFood;
use App\Models\UserNotification;
//use Illuminate\Support\Facades\Storage;

class Food_controller extends Controller
{
 

    public function show()
    {
        $foods = Food::all();

        foreach ($foods as $food) 
        {
            //$food->food_path =env('APP_URL') ;//'APP_URL';
            $food->food_path="storage/";
            $food->food_path .= $food->img;
            $food->img = str_replace('\\', '/', $food->food_path);
           
            //$food->path=$food->food_path;
        }
        //return $foods;//->food_path;
        //$food = Food::where('id',$request->id)->firstOrFail();
        
        $response= APIHelpers::createAPIResponse(false,200,' here is all foods',$foods,1);
        return response()->json($response,200);
    }

    public function show_image(Request $request){

        $food = Food::where('id',$request->id)->firstOrFail();
        
        $food_path =env('APP_URL') ;//'APP_URL';
        $food_path.="storage/";
        $food_path .= $food->img;
        return $food_path;
        //return Storage::disk('public');//->response($food);
        

        //$path = $food_path;
       // return response()->file($path);        
    }


    public function order_food(Request $request)
    {
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;

        $userroom = UserRoom::where('user_id',$user_id)->where('status_id',2)->first();
        $user_room_id=$userroom->id;

        if($userroom == NULL)
        {
            $account_status =0;
            $response= APIHelpers::createAPIResponse(true,200,'you have not checked_in',$account_status,0);
            return response()->json($response,200);
      
        }

        $food=UserRoomFood::create([
            "user_room_id"=>$user_room_id,
            "food_id"=>$request->food_id,
            "count"=>$request->count,
            "notes"=>$request->notes,
            "status_id"=>4,
            
        ]);
        $response= APIHelpers::createAPIResponse(false,201,'Food request added',$food,1);
        return response()->json($response,201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_file ='';
        if($request->hasFile('img')){
            $file=$request->img;
            $new_file = time().$file-> getClientOriginalName();
            $file->move('/storage/food_img/'. $new_file);
        }

        $foods=Food::create([
            "title"=>$request->title,
            "img"=>'/storage/food_img'. $new_file,
            "desc"=>$request->desc,
            "price"=>$request->price,
        ]);
        $response= APIHelpers::createAPIResponse(false,201,'',$foods,1);
        return response()->json($response,201);
        
    }

 
    public function index($id)
    {
        $foods = Food::find($id);
        $response= APIHelpers::createAPIResponse(false,200,'',$foods,1);
        return response()->json($response,200);
    }

    
    public function edit($id)
    {
        $foods = Food::find($id);
        $response= APIHelpers::createAPIResponse(false,200,'',$foods,1);
        return response()->json($response,200);
    }

    
    public function update(Request $request, $id)
    {
        $new_file = ''; 
        if($request->hasFile('img')){
            $file=$request->img;
            $new_file = time().$file-> getClientOriginalName();
            $file->move('/storage/food_img/'. $new_file);
        }

        $foods = Food::find($id);
        $foods-> title= $request->title;
        $foods-> desc= $request->desc;
        $foods-> img= '/storage/food_img'. $new_file;
        $foods-> price= $request->price;
        $foods->update();

        if (is_null($foods)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$foods,1);
            return response()->json($response,404);
        }
        
        $response= APIHelpers::createAPIResponse(false,200,'',$foods,1);
        return response()->json($response,200);
    }

   
    public function destroy($id)
    {
        $foods= Food::find($id);
        $foods->destroy($id);
        if (is_null($foods)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$foods,1);
            return response()->json($response,404);
        }
        $response= APIHelpers::createAPIResponse(false,200,'delete success',[],1);
        return response()->json($response,200);
    }

    public function show_food_order()
    {
        $orders = UserRoomFood::all();

        $response= APIHelpers::createAPIResponse(false,200,'here all orders ',$orders,1);
        return response()->json($response,200);

    }

    public function accept_order(Request $request)
    {
        
        $data = UserNotification::create([
            "notification_id"=>$request->notification_id,
            "user_id"=>$request->user_id,
            "status_id"=>$request->status_id,
        ]); 
        
        $response= APIHelpers::createAPIResponse(false,200,'here all orders ',$data,1);
        return response()->json($response,200);

    }
}