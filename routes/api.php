<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoFrancoController;
use Illuminate\Support\Facades\Auth;

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

/*
Route::prefix('auth')->group(function () {
    Route::post('login', AuthController::class . '@login');
    Route::post('register', AuthController::class . '@register');
    Route::post('mood', AuthController::class  . '@mood');
});

*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', AuthController::class . '@login');
    Route::post('refresh', AuthController::class . '@refresh');
    Route::post('logout', AuthController::class . '@logout');
    Route::post('mood', AuthController::class . '@mood');
});

//route::post('auth/register', [ 'as' => 'login', 'uses' => AuthController::class.'@register']);


Route::group(['middleware' => ['apiJwt']], function () {
    route::get('mood', TodoFrancoController::class . '@mood');
    Route::get('todos', TodoFrancoController::class . '@index');
    Route::post('todo', TodoFrancoController::class . '@store');
    Route::get('todo/{id}', TodoFrancoController::class . '@show');
    Route::put('todo/{id}', TodoFrancoController::class . '@update');
    Route::delete('todo/{id}', TodoFrancoController::class . '@destroy');
});
