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

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return response()->json(['data' => $request->user()]);
});

Route::group([
    'as' => 'rooms.',
    'prefix' => 'rooms',
    'middleware' => ['auth:api']
], function () {
    Route::get('{room}/users', 'RoomsController@getUsers')->name('users');
    Route::get('{room}/tasks', 'RoomsController@getTasks')->name('tasks');
});


Route::middleware(['auth:api'])->apiResource('rooms', 'RoomsController');
Route::middleware(['auth:api'])->apiResource('tasks', 'TasksController');

