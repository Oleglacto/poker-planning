<?php

namespace App\Http\Controllers;

use App\Contracts\RoomManagementInterface;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SwooleTW\Http\Websocket\Facades\Websocket;

class RoomsController extends Controller
{
    private RoomManagementInterface $roomManagementService;

    public function __construct(RoomManagementInterface $roomManagementService)
    {
        $this->roomManagementService = $roomManagementService;
    }

    public function index()
    {
        $rooms = Room::paginate(50);

        /**
         * Костыль
         */
        $users = DB::table('user_room')->whereIn('room_id', $rooms)->get()->groupBy('room_id');

        $rooms->each(function (Room $room) use ($users) {
            $room->clients = optional(optional($users->get($room->getKey()))->pluck('user_id'))->toArray() ?? [];
            $room->clients_count = count($room->clients);
        });


        return response()->json(['data' => $rooms]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'string'
        ]);

        $room = Room::create($request->all());
        Websocket::broadcast()->emit('roomCreated', $room);
        return $room;
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function getUsers(Room $room)
    {
        return response()->json(['data' => User::whereIn('id', $this->roomManagementService->getUserIdsInRoom($room->getKey()))->get()]);
    }

    public function getTasks(Room $room)
    {
        return response()->json(['data' => $room->tasks()->get()]);
    }

    private function getRoomName(Room $room)
    {
        return 'room' . $room->getKey();
    }
}
