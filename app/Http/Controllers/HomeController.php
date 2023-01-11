<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Models\Room;
use App\Models\UserRoom;
use App\Models\UserRoomFood;
use App\Models\UserRoomService;
use App\Models\User;
use App\Models\food;
use App\Models\Service;
use App\Models\UserNotification;
use App\Models\Notification;
use TCG\Voyager\Facades\Voyager;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Validator;
use Session;
use DateTime;
use TCG\Voyager\Http\Controllers\ContentTypes\Timestamp;

class HomeController extends Controller
{
   
    //Login Api
    public function login(Request $request)
    {
        
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $id = $user->id;
        $is_done = UserRoom::where('user_id', $id)->where('status_id',2)->exists();
        if($is_done){
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.' welcome to home','access_token' => $token, 'token_type' => 'Bearer', 'checkIn' => true]);
        
        }
        
        else{
            return response()
            ->json(['message' => 'Hi '.$user->name.' welcome to home','access_token' => 's', 'token_type' => 'Bearer', 'checkIn' => false]);
        }
    }
    

    //Register Api

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
     
        $user = User::create([
            'fname' =>$request->fname,
            'lname' =>$request->lname,
            'email' => $request->email,
            'phone_num'=>$request->mobileNumber,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
         
    }



    //logout :

    public function logout(Request $request)
    {
        if ($request->user()){

            $request->user()->tokens()->delete();

        }
        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }


    //get all notification

    /*public function get_notify(Request $request)
    {
        $noti=[];
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;
        // return $user_id;
        $food = Food::where('id',$request->id)->firstOrFail();
       $n =  UserNotification::where('id',$user_id)->all();
      
        foreach($n as $row)
        {
            foreach($row as $nn)
            {
             
                $noti[] = Notification::where('id',$nn)->get();
            }
        }
            return response()
       ->json($n);
    }
    
        
*/
        public function get_notify(Request $request)
    {
        
        $user = User::where('email',$request->email)->firstOrFail();
        $user_id = $user->id;
        
       $un=  UserNotification::where('user_id',$user_id)->pluck('notification_id')->toArray();
      // return $n;
      
        foreach($un as $not)
        {
                $noti = Notification::where('id',$not)->get();
                //'updated_at'=Timestamp();
            
        }
        
       // return $noti;
       //return response()->json(['notification' => $noti]);
       $response= APIHelpers::createAPIResponse(false,200,'you have not checked_in',$noti,1);
       return response()->json($response,200);
    }

   


    public function invoice($test)
    {

        $res = UserRoom::FindOrFail($test); 
        $user = User::FindOrFail($res->user_id);  
        $room = Room::FindOrFail($res->room_id);
    
        //room price
        $invoice = $res->duration * $room->price;


        $total=0;
        $user_room_food  = UserRoomFood::where('user_room_id',$res->id)->get();
    
        $user_room_Services = UserRoomService::where('user_room_id',$res->id)->get();
    
        // $other_Service = json_decode(json_encode($other_Service));


        $food_detail = [];
        $foods_invoice=[];
        $sum =0;
       

        for($i=0;$i<count($user_room_food);$i++)
        {
            $food_detail = food::where('id',$user_room_food[$i]->food_id)->get(['title','price']);
            //return $food_id;
            for($j=0;$j<count($food_detail);$j++)
                {
            
                 $total += $user_room_food[$i]->count * $food_detail[$j]->price;
                 $foods_invoice []= [
                 'title'=> $food_detail[$j]->title,
                 'count'=>  $user_room_food[$i]->count,
                 'price' =>  $food_detail[$j]->price,
                 'total' =>$total
                    ];
                    $i++;
                }           
        }

        $service_invoice=[];
        $service_sum=0;
        //--------------------------------------------------------------
        for($i=0;$i<count($user_room_Services);$i++)
        {
            $serivce_id = Service::where('id',$user_room_Services[$i]->service_id)->get(['title','price']);
        

            for($j=0;$j<count($serivce_id);$j++)
            {
                $service_sum+=$serivce_id[$j]->price;
                $service_invoice []= [
                'title'=> $serivce_id[$j]->title,
                'price' =>  $serivce_id[$j]->price,
                ];
                $i++;
            }    
   
        }

        //--------------------------------------------------------------


        $final_invoice = ([
        'resid'=>$res->id,
        'fname' => $user->fname,
        'room_id'=>$room->id,
        'room_price' => $room->price,
        'duration' =>$res->duration,
        'Total' => ($total + $invoice+ $service_sum)]);

        return view('invoice')->with('final_invoice',($final_invoice))
        ->with('cnt', $foods_invoice)
        ->with('service_invoice', $service_invoice);  

    }


    public function generatePDF($test)
    {

        $res = UserRoom::FindOrFail($test); 
        $user = User::FindOrFail($res->user_id);  
        $room = Room::FindOrFail($res->room_id);
    
        //room price
        $invoice = $res->duration * $room->price;


        $total=0;
        $user_room_food  = UserRoomFood::where('user_room_id',$res->id)->get();
    
        $user_room_Services = UserRoomService::where('user_room_id',$res->id)->get();
    
        // $other_Service = json_decode(json_encode($other_Service));


        $food_detail = [];
        $foods_invoice=[];
        $sum =0;
       

        for($i=0;$i<count($user_room_food);$i++)
        {
            $food_detail = food::where('id',$user_room_food[$i]->food_id)->get(['title','price']);
            //return $food_id;
            for($j=0;$j<count($food_detail);$j++)
                {
            
                 $total += $user_room_food[$i]->count * $food_detail[$j]->price;
                 $foods_invoice []= [
                 'title'=> $food_detail[$j]->title,
                 'count'=>  $user_room_food[$i]->count,
                 'price' =>  $food_detail[$j]->price,
                 'total' =>$total
                    ];
                    $i++;
                }           
        }

        $service_invoice=[];
        $service_sum=0;
        //--------------------------------------------------------------
        for($i=0;$i<count($user_room_Services);$i++)
        {
            $serivce_id = Service::where('id',$user_room_Services[$i]->service_id)->get(['title','price']);
        

            for($j=0;$j<count($serivce_id);$j++)
            {
                $service_sum+=$serivce_id[$j]->price;
                $service_invoice []= [
                'title'=> $serivce_id[$j]->title,
                'price' =>  $serivce_id[$j]->price,
                ];
                $i++;
            }    
   
        }

        //--------------------------------------------------------------


        $final_invoice = ([
        'resid'=>$res->id,
        'fname' => $user->fname,
        'room_id'=>$room->id,
        'room_price' => $room->price,
        'duration' =>$res->duration,
        'Total' => ($total + $invoice+ $service_sum)]);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $dompdf->loadHtml(view('pdf')
        
        ->with('final_invoice',($final_invoice))
        ->with('cnt', $foods_invoice)
        ->with('service_invoice', $service_invoice));
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream($user->name.'_invoice.pdf');

    }


    public function search_result(Request $request)
    {
       $num_of_persons =  $request->get('search');
       

        $rooms = Room::where('person_num',$num_of_persons)->get()->toArray();
    
        $reservations=[];
        foreach($rooms as $room)
        {
            array_push($reservations,UserRoom::where('room_id',$room['id'])->get());
        }

        Session::put('reservations', json_decode(json_encode ($reservations)));

        return redirect('admin/user-rooms')->with('reservations',$reservations);
    }

}
