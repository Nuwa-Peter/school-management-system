<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'location',
        'description',
        'is_bookable',
    ];

    /**
     * Get the bookings for the resource.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(ResourceBooking::class);
    }
}
