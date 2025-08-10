<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'checkout_date',
        'due_date',
        'returned_date',
        'fine_amount',
        'checked_out_by_id',
        'checked_in_by_id',
    ];

    protected $casts = [
        'checkout_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    /**
     * Get the book that was checked out.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the student who checked out the book.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the librarian who checked out the book.
     */
    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by_id');
    }

    /**
     * Get the librarian who checked in the book.
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by_id');
    }
}
