<?php

use Illuminate\Http\Request;

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

//Activities routes
Route::get('/activities', 'ActivityController@find');
Route::get('/activities/{id}','ActivityController@findById');
Route::post('/activities', 'ActivityController@create');
Route::put('/activities/{id}', 'ActivityController@update');
Route::delete('/activities/{id}', 'ActivityController@delete');
