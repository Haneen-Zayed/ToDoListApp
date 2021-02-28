<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});





 // added route user to verify email


Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('forgot', [ForgotController::class,'forgot']);
Route::post('reset', [ForgotController::class,'reset']);

// routes for pass app or Editing Task  

Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::get('logout', [AuthController::class, 'logout'])->name('user.logout');
Route::middleware('auth:api')->group(function(){
	Route::get('showTodayTaskOngoing', [TaskController::class, 'showTodayTaskOngoing']);
	Route::get('showTomorrowTask', [TaskController::class, 'showTomorrowTask']);
	Route::get('showTaskCompleted', [TaskController::class, 'showTaskCompleted']);
	Route::post('createTodayTask', [TaskController::class, 'createTodayTask']);
	Route::get('makeTaskCompletedOrOngoing/{id}', [TaskController::class, 'makeTaskCompletedOrOngoing']);
	Route::post('createTomorrowTask', [TaskController::class, 'createTomorrowTask']);
	Route::post('updatetask/{id}', [TaskController::class, 'update']);
	Route::get('transportTask/{id}', [TaskController::class, 'transportTask']);
	Route::get('delete/{id}', [TaskController::class, 'destroy']);

	});
