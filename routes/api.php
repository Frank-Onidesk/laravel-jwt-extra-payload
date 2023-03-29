<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoFrancoController;


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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */




route::post('auth/login', [ 'as' => 'login', 'uses' => AuthController::class.'@login']);


Route::group(['middleware' => ['apiJwt']], function(){
    Route::get('todos', TodoFrancoController::class.'@index');
    Route::post('todo', TodoFrancoController::class.'@store');
    Route::get('todo/{id}', TodoFrancoController::class.'@show');
    Route::put('todo/{id}', TodoFrancoController::class.'@update');
    Route::delete('todo/{id}', TodoFrancoController::class.'@destroy');
});