<?php

namespace App\Http\Controllers\backend;

use App\Helpers\APIHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Room;
use Validator;
class Rooms_controller extends Controller
{
   
    
    public function show()
    {
        $rooms = Room::all();
        $response= APIHelpers::createAPIResponse(false,200,'',$rooms,1);
        return response()->json($response,200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'num' => 'required|unique:rooms',
            'person_num' => 'required',
            'bath_num' => 'required',
            'desc' => 'required',
            'type' => 'required',
            'floor_num' => 'required',
            'availability' => 'required',
            'price' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }
        $rooms= Room::create([
            "num"=> $request->num,
            "person_num"=>$request->person_num,
            "bath_num"=>$request->bath_num,
            "desc"=>$request->desc,
            "type"=>$request->type,
            "floor_num"=>$request->floor_num,
            "availability"=>$request->availability,
            "price"=>$request->price,
        ]);

        $response= APIHelpers::createAPIResponse(false,201,'',$rooms,1);
        return response()->json($response,200);
    }

    
    public function index($id)
    {
        $rooms= Room::find($id);

        if (is_null($rooms)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$rooms,1);
            return response()->json($response,404);
        }

        $response= APIHelpers::createAPIResponse(false,200,'',$rooms,1);
        return response()->json($response,200);
    }


    public function update(Request $request, $id)
    {
        $data = $request->json()->all();
        $rooms= Room::find($id);
        $rooms->update($data);

        if (is_null($rooms)){
            $response= APIHelpers::createAPIResponse(true,404,'not found',$rooms,1);
            return response()->json($response,404);
        }

        $response= APIHelpers::createAPIResponse(false,200,'',$rooms,1);
        return response()->json($response,200);

    }

    

    public function destroy($id)
    {
        $rooms= Room::find($id);
        $rooms->destroy($id);
        if (is_null($rooms)){
            $response= APIHelpers::createAPIResponse(false,404,'not found',$rooms,1);
            return response()->json($response,404);
        }
        
        $response= APIHelpers::createAPIResponse(false,200,'deleted',[],1);
        return response()->json($response,200);

    }
}
