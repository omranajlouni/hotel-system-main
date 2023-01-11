<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Models\Review;

class admin_controller extends Controller
{
    public function show_report()
    {
        //here show the report to admin

    }
    public function show_review()
    {
        //here show report of reviews
        $reviews = Review::all();
        $response= APIHelpers::createAPIResponse(false,200,'here is all reviews',$reviews);
        return response()->json($response,200);


    }
    
    
}
