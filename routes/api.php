<?php
use App\Http\Controllers\backend\Rooms_controller;
use App\Http\Controllers\backend\Food_controller;
use App\Http\Controllers\backend\Reserve_controller;
use App\Http\Controllers\backend\Service_controller;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Room;
use App\Helpers\APIHelpers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'reserve'],function(){
    //here to the customer not hotel guest
   // Route::get('/fill_form',[Reserve_controller::class,'form']);
    //here to hotel guest
    Route::post('/add_reserve',[Reserve_controller::class,'store'])->name('reserve.store');
    Route::get('/accept_reserve',[Reserve_controller::class,'accept'])->name('reserve.accept');
    Route::get('/accept_extend',[Reserve_controller::class,'accept_extend'])->name('reserve.accept_extend');
});


Route::group(['prefix'=>'rooms'],function(){
    Route::Post('/add_room/store',[Rooms_controller::class,'Store'])->name('rooms.store');
    Route::get('/show_room',[Rooms_controller::class,'show'])->name('rooms.show');
    Route::get('/show_room/{id}',[Rooms_controller::class,'index'])->name('rooms.index');
    Route::post('/update/{id}',[Rooms_controller::class,'update'])->name('rooms.update');
    Route::post('/delete/{id}',[Rooms_controller::class,'destroy'])->name('rooms.destroy');
});

Route::group(['prefix' => 'foods'],function(){
    Route::Post('/add_food/store',[Food_controller::class,'Store'])->name('food.store');
    Route::get('/show_food/{id}',[Food_controller::class,'index'])->name('food.index');
    Route::get('/show_food',[Food_controller::class,'show'])->name('food.show');
    Route::post('/show_image',[Food_controller::class,'show_image'])->name('food.show_image');
    Route::get('/show_food/edit/{id}',[Food_controller::class,'edit'])->name('food.edit');
    Route::post('/update/{id}',[Food_controller::class,'update'])->name('food.update');
    Route::post('/delete/{id}',[Food_controller::class,'destroy'])->name('food.destroy');
    Route::Post('/order_food',[Food_controller::class,'order_food'])->name('food.order_food');
    Route::get('/show_food_order',[Food_controller::class,'show_food_order'])->name('food.show_order');
    Route::get('/show_food_order/accept',[Food_controller::class,'accept_order'])->name('food.accept_order');
});


Route::group(['prefix' => 'service'],function(){
    Route::Post('/add_service_request',[Service_controller::class,'add_service_request']);
    Route::get('/show_all_service_request',[Service_controller::class,'show'])->name('service.show');
    Route::post('/show_service',[Service_controller::class,'show_service'])->name('service.show_service');
    Route::get('/show_service_request/{id}',[Service_controller::class,'index'])->name('service.index');
    Route::Post('/show_service_request/accept',[Service_controller::class,'accept'])->name('service.accept');
    Route::Post('/show_service_request/decline',[Service_controller::class,'decline'])->name('service.decline');
    //to see reservation services
    Route::get('/show_user_room_services',[Service_controller::class,'show_user_room_services'])->name('service.show_user_room_services');
   

});

//User Register and login
Route::post('/register', [HomeController::class, 'register']);
//API route for login user
Route::post('/login', [HomeController::class, 'login']);

// API route for logout user
Route::post('/logout', [HomeController::class, 'logout']);

//get all notification : 
Route::post('/noti', [HomeController::class, 'get_notify']);