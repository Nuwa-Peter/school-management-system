<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'teacher_in_charge_id',
    ];

    /**
     * Get the teacher in charge of the club.
     */
    public function teacherInCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_in_charge_id');
    }

    /**
     * The students that are members of this club.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_members', 'club_id', 'user_id');
    }
}
