<?php

namespace App\Services;

use App\Contracts\RoomManagementInterface;
use Illuminate\Support\Facades\Cache;

class CacheRoomManagementService implements RoomManagementInterface
{
    public function addUserIdToRoom(int $userId, int $roomId): void
    {
        $roomName = RoomNameGenerator::generate($roomId);
        $usersInRoom = Cache::get($roomName) ?? [];

        $usersInRoom[] = $userId;

        Cache::forever(RoomNameGenerator::generate($roomId), array_unique($usersInRoom));
    }

    public function getUserIdsInRoom(int $roomId): array
    {
        $roomName = RoomNameGenerator::generate($roomId);
        return $usersInRoom = Cache::get($roomName) ?? [];
    }

    public function removeUserIdFromRoom(int $userId, int $roomId): void
    {
        $roomName = RoomNameGenerator::generate($roomId);
        $usersInRoom = Cache::get($roomName) ?? [];

        $usersInRoom = array_filter($usersInRoom, fn($item) => $item !== $userId);
        Cache::forever(RoomNameGenerator::generate($roomId), $usersInRoom);
    }

    public function getUserRoomIds(int $userId): array
    {
        // TODO: Implement getUserRoomIds() method.
    }

    public function removeUserIdFromAllRooms(int $userId): void
    {
        // TODO: Implement removeUserIdFromAllRooms() method.
    }
}
