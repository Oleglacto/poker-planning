<?php

namespace App\Http\Controllers;

use App\Contracts\RoomManagementInterface;
use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;


class WebsocketController
{
    private RoomManagementInterface $roomManagement;

    public function __construct(RoomManagementInterface $roomManagement)
    {
        $this->roomManagement = $roomManagement;
    }

    public function disconnect($websocket, Request $request)
    {
        if (is_null($websocket->getUserId())) {
            return;
        }
        $this->roomManagement->removeUserIdFromAllRooms($websocket->getUserId());
        dump('disconnected: ' . $websocket->getUserId());
        Websocket::broadcast()->emit('userDisconnected', [
            'user' => auth()->user(),
            'user_id' => $websocket->getUserId()
        ]);
    }
}
