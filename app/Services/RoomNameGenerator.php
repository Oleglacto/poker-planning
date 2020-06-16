<?php

namespace App\Services;

class RoomNameGenerator
{
    protected const PREFIX = "room_";

    public static function generate(int $roomId)
    {
        return RoomNameGenerator::PREFIX  . $roomId;
    }
}
