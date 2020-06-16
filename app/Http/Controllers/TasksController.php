<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
//use SwooleTW\Http\Websocket\Facades\Websocket;
use SwooleTW\Http\Websocket\Facades\Room as SwooleRoom;
use SwooleTW\Http\Websocket\Websocket;

class TasksController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Room::paginate(50)]);
    }
    public function store(Websocket $websocket, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'string',
            'room_id' =>'required|integer'
        ]);

        $task = Task::create($request->all());

        $websocket->broadcast()->emit('taskCreated', [
            'task' => $task,
            'room_id' => $request->get('room_id')
        ]);

        return $task;
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function getUsers(Room $room)
    {
        dump('userInRoom', SwooleRoom::getClients($this->getRoomName($room)));
        return response()->json(['data' => User::whereIn('id', SwooleRoom::getClients($this->getRoomName($room)))->get()]);
    }

    private function getRoomName($roomId)
    {
        return 'room' . $roomId;
    }
}
