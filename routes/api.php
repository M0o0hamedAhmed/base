<?php

use App\Http\Controllers\AP1\V1\AuthController;
use App\Http\Controllers\AP1\V1\Main\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['middleware' => 'api', 'prefix' => 'v1/{lang}', 'where' => ['lang' => 'en|ar']], function ($router) {

    // not auth
    Route::post('login', [AuthController::class, 'login']);





    // must authentication
    Route::group(['middleware' => ['auth']], function ($router) {

        //Auth
        Route::controller(AuthController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refresh');
            Route::get('me', 'me');
        });



        Route::apiResources([
            'user' => UserController::class,
        ]);

    });

});
