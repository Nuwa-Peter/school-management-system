<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dormitory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
    ];

    /**
     * Get the rooms for the dormitory.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(DormitoryRoom::class);
    }
}
