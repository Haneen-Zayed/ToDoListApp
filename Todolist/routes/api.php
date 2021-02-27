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

Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::get('logout', [AuthController::class, 'logout'])->name('user.logout');
Route::middleware('auth:api')->group(function(){
	Route::get('showTodayTaskOngoing', [TaskController::class, 'showTodayTaskOngoing']);
	Route::get('showTomorrowTask', [TaskController::class, 'showTomorrowTask']);
	Route::get('showTaskCompleted', [TaskController::class, 'showTaskCompleted']);
	Route::post('createTodayTask', [TaskController::class, 'createTodayTask']);
	Route::get('showTaskCompleted', [TaskController::class, 'showTaskCompleted']);
	Route::get('makeTaskCompletedOrOngoing/{id}', [TaskController::class, 'makeTaskCompletedOrOngoing']);
	Route::post('createTomorrowTask', [TaskController::class, 'createTomorrowTask']);
	Route::get('updatetask/{id}', [TaskController::class, 'update']);
	Route::get('transportTask/{id}', [TaskController::class, 'transportTask']);
	Route::get('delete/{id}', [TaskController::class, 'destroy']);
	Route::get('deleteCompleted', [TaskController::class, 'deleteCompletedTasks']);
	Route::get('date', [TaskController::class, 'date']);



	});



 


/* Route::middleware('auth:api')->group(function(){
	 Route::resource('tasks', 'API\TaskController');
	Route::get('showTodayTaskOngoing', 'API\TaskController@showTodayTaskOngoing');
});
*/

