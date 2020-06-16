<?php

namespace App\Services;

use App\Contracts\RoomManagementInterface;
use Illuminate\Support\Facades\DB;


class DbRoomManagementService implements RoomManagementInterface
{
    public function addUserIdToRoom(int $userId, int $roomId): void
    {
        try {
            DB::table('user_room')->insert([
                'user_id' => $userId,
                'room_id' => $roomId
            ]);
        } catch (\Throwable $exception) {
            // nothing
        }

    }

    public function getUserIdsInRoom(int $roomId): array
    {
         $result = DB::table('user_room')
            ->where('room_id', $roomId)
            ->get()
            ->pluck('user_id')
            ->toArray();

         dump($result, '--', $roomId);
         return $result;
    }

    public function removeUserIdFromRoom(int $userId, int $roomId): void
    {
        DB::table('user_room')
            ->where('room_id', $roomId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function getUserRoomIds(int $userId): array
    {
        return DB::table('user_room')
            ->where('user_id', $userId)
            ->get()
            ->pluck('room_id')
            ->toArray();
    }

    public function removeUserIdFromAllRooms(int $userId): void
    {

        DB::table('user_room')
            ->where('user_id', $userId)
            ->delete();
    }
}
