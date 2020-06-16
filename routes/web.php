<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SwooleTW\Http\Websocket\Facades\Room as SwooleRoom;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'api'], function () {

});



Route::get('/test', function () {
    dd(
        SwooleRoom::getClients('room.1'),
        SwooleRoom::getRooms(1)
    );
});


Auth::routes();

Route::get('/{any?}', function () {
    return view('main');
})->where('any', '[\/\w\.-]*')->middleware('auth');

