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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

 // added route user to verify email


Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('forgot', [ForgotController::class,'forgot']);




 // Route task

Route::middleware('auth:api')->group(function(){
	 Route::resource('tasks', 'API\TaskController');
/*	Route::get('notes/userNotes/{id}', 'API\NoteController@userNotes');
	Route::post('notes/add', 'API\NoteController@store');
	Route::get('note/{id}', 'API\NoteController@show');
//	Route::post('notes/edit', 'API\NoteController@update');
	Route::delete('notes/delete/{id}', 'API\NoteController@destroy');

*/
});
