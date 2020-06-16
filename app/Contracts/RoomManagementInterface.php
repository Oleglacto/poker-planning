<?php

namespace App\Contracts;

interface RoomManagementInterface
{
    public function addUserIdToRoom(int $userId, int $roomId): void;
    public function getUserIdsInRoom(int $roomId): array;
    public function removeUserIdFromRoom(int $userId, int $roomId): void;
    public function getUserRoomIds(int $userId): array;
    public function removeUserIdFromAllRooms(int $userId): void;
}
