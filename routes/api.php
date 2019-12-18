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


//config routes
//TODO : delete once set
Route::get('/makelink', 'StorageLink@makeStorageLink');

//Activities routes
Route::get('/activities', 'ActivityController@find');
Route::get('/activities/{id}','ActivityController@findById');
Route::post('/activities', 'ActivityController@create');
Route::put('/activities/{id}', 'ActivityController@update');
Route::delete('/activities/{id}', 'ActivityController@delete');

//Users routes
Route::post('/register','UserController@register');
Route::post('/login','UserController@login');
Route::get('/logout', 'UserController@logout');
