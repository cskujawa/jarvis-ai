<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NHTSA;
use Illuminate\Support\Facades\Log;

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

Route::middleware('api')->group(function () {
    Route::get('/nhtsa/years', function () {
        return NHTSA::getModelYears();
    });
    Route::post('/nhtsa/makes', function (Request $request) {
        return NHTSA::getMakes($request['params']['year']);
    });
    Route::post('/nhtsa/models', function (Request $request) {
        return NHTSA::getModels($request['params']['year'], $request['params']['make']);
    });
    Route::post('/nhtsa/variants', function (Request $request) {
        return NHTSA::getVariants($request['params']['year'], $request['params']['make'], $request['params']['model']);
    });
    Route::post('/nhtsa/safety', function (Request $request) {
        return NHTSA::getSafety($request['params']['id']);
    });
});