<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'published_year',
        'quantity',
        'available_quantity',
    ];

    /**
     * Get the checkout records for the book.
     */
    public function checkouts(): HasMany
    {
        return $this->hasMany(BookCheckout::class);
    }
}
