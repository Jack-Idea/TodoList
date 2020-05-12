<?php

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


Route::get('/data', 'TodoController@apiShow');
Route::post('/task/submit', 'TodoController@submit');
Route::delete('/welcome/{id}', 'TodoController@removeCard');
Route::post('/welcome/{id}/edit', 'TodoController@editFlag');
Route::post('/welcome/{id}/title', 'TodoController@editTitle');