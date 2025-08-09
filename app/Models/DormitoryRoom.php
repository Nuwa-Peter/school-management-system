<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DormitoryRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'dormitory_id',
        'room_number',
        'capacity',
    ];

    /**
     * Get the dormitory that the room belongs to.
     */
    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(Dormitory::class);
    }

    /**
     * Get the student assignments for the room.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(RoomAssignment::class);
    }
}
