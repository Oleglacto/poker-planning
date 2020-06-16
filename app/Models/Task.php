<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SwooleTW\Http\Websocket\Facades\Room as SwooleRoom;

class Task extends Model
{
    protected $primaryKey = 'task_id';

    protected $table = 'tasks';

    protected $fillable = [
        'name', 'description', 'room_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
}
