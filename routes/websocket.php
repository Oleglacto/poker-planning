<?php

use App\Models\User;
use SwooleTW\Http\Websocket\Facades\Room;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('connect', function (Websocket $websocket, $request) {
    $websocket->loginUsing($request->user());
});

Websocket::on('disconnect', 'App\Http\Controllers\WebsocketController@disconnect');


Websocket::on(
    'joinToRoom',
    'App\Http\Controllers\Ws\RoomsController@joinToRoom'
);

Websocket::on('leaveFromRoom', 'App\Http\Controllers\Ws\RoomsController@leaveFromRoom');
Websocket::on('voteStarted', 'App\Http\Controllers\Ws\RoomsController@voteStarted');
Websocket::on('userIsVoted', 'App\Http\Controllers\Ws\RoomsController@userIsVoted');

