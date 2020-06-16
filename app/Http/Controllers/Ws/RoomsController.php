<?php

namespace App\Http\Controllers\Ws;

use App\Contracts\RoomManagementInterface;
use App\Models\User;
use App\Services\RoomNameGenerator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SwooleTW\Http\Websocket\Websocket;

class RoomsController
{
    private RoomManagementInterface $roomManagementService;

    public function __construct(RoomManagementInterface $roomManagementService)
    {
        $this->roomManagementService = $roomManagementService;
    }

    public function joinToRoom(Websocket $websocket, $data)
    {
        $this->roomManagementService->addUserIdToRoom($data['user_id'], $data['room_id']);

        $user = User::find($data['user_id']);

        $websocket->join(RoomNameGenerator::generate($data['room_id']));

        $websocket->broadcast()->emit('userJoinedToRoom', [
            'room_id' => $data['room_id'],
            'user_id' => $user->getKey(),
            'user'    => $user
        ]);
    }

    public function leaveFromRoom(Websocket $websocket, $data)
    {
        $user = User::find($data['user_id']);

        $websocket->broadcast()->emit('userLeavedFromRoom', [
            'room_id' => $data['room_id'],
            'user_id' => $data['user_id'],
            'user'    => $user
        ]);

        $websocket->leave(RoomNameGenerator::generate($data['room_id']));

        $this->roomManagementService->removeUserIdFromRoom($data['user_id'], $data['room_id']);
    }

    public function voteStarted(Websocket $websocket, $data)
    {
        Cache::put(RoomNameGenerator::generate($data['room_id']), count($this->roomManagementService->getUserIdsInRoom($data['room_id'])), now()->addMinutes(10));

        $websocket->to(RoomNameGenerator::generate($data['room_id']))->emit('voteStarted', [
            'task_id' => $data['task_id']
        ]);
    }

    public function userIsVoted(Websocket $websocket, $data)
    {
        DB::table('user_room_vote')->insert([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'vote'    => $data['form']['value']
        ]);

        if (Cache::decrement(RoomNameGenerator::generate($data['room_id'])) === 0) {
            $result = DB::table('user_room_vote')->where('room_id', $data['room_id'])->get()->toArray();
            DB::table('user_room_vote')->where('room_id', $data['room_id'])->delete();

            $websocket->to(RoomNameGenerator::generate($data['room_id']))->emit('voteFinished', [
                'result' => $result
            ]);
        }

        $websocket->to(RoomNameGenerator::generate($data['room_id']))->emit('userIsVoted', [
            'user_id' => $data['user_id'],
            'value' => $data['form']['value']
        ]);
    }
}
