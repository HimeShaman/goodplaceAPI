<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

//Auth route
Route::middleware('auth:api')->group(function () {
    Route::post('/activities', 'ActivityController@create');
    Route::put('/activities/{id}', 'ActivityController@update');
    Route::delete('/activities/{id}', 'ActivityController@delete');
});

//config routes
//TODO : delete once set
Route::get('/makelink', function() {
    Artisan::call('storage:link');
});

//Activities routes
Route::get('/activities', 'ActivityController@find');
Route::get('/activities/{id}','ActivityController@findById');



//Users routes
Route::post('/register','UserController@register');
Route::post('/login','UserController@login');
Route::get('/logout', 'UserController@logout');
