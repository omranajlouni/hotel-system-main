<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\UserRoomFoodController;
use App\Models\Room;
use App\Models\UserRoom;
use App\Models\UserRoomFood;
use App\Models\food;
use App\Models\User;
use App\Http\Controllers\HomeController;
use Dompdf\Dompdf;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


//chart
Route::get('index', [HomeController::class, 'chartjs']);

route::get('invoice/{test}',[HomeController::class, 'invoice'])->name('intest');

route::get('pdf/{test}',[HomeController::class, 'generatePDF'])->name('generatePDF');

route::get('search',[HomeController::class, 'custom_search'])->name('custom_search');

route::get('result',[HomeController::class, 'search_result'])->name('search_result');


