<?php

namespace App\Models;

use App\Contracts\RoomManagementInterface;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $primaryKey = 'room_id';

    protected $table = 'rooms';

    protected $fillable = [
        'name', 'description',
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'room_id', 'room_id');
    }
}
