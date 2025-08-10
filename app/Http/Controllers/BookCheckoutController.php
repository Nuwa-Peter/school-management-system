<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCheckout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookCheckoutController extends Controller
{
    /**
     * Display a listing of the checked-out books.
     */
    public function index(Request $request): View
    {
        $checkouts = BookCheckout::with(['book', 'student'])
            ->whereNull('returned_date')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', fn($q) => $q->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('book', fn($q) => $q->where('title', 'like', "%{$search}%"));
            })
            ->latest('checkout_date')
            ->get();

        return view('library.checkouts.index', compact('checkouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('last_name')->get();
        $books = Book::where('available_quantity', '>', 0)->orderBy('title')->get();
        return view('library.checkouts.create', compact('students', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
        ]);

        $book = Book::find($request->book_id);

        if ($book->available_quantity <= 0) {
            return back()->with('error', 'This book is currently not available.');
        }

        DB::beginTransaction();
        try {
            BookCheckout::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'checkout_date' => now(),
                'due_date' => $request->due_date,
                'checked_out_by_id' => Auth::id(),
            ]);

            $book->decrement('available_quantity');

            DB::commit();

            return redirect()->route('checkouts.index')->with('success', 'Book checked out successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while checking out the book.');
        }
    }

    /**
     * Mark a book as returned.
     */
    public function update(BookCheckout $checkout): RedirectResponse
    {
        if ($checkout->returned_date) {
            return redirect()->route('checkouts.index')->with('error', 'This book has already been returned.');
        }

        DB::beginTransaction();
        try {
            $checkout->update([
                'returned_date' => now(),
                'checked_in_by_id' => Auth::id(),
            ]);

            $checkout->book->increment('available_quantity');

            // Basic fine calculation logic (e.g., $1 per day overdue)
            if ($checkout->due_date->isPast()) {
                $daysOverdue = $checkout->due_date->diffInDays(now());
                $fine = $daysOverdue * 1.00; // Example: $1 per day
                $checkout->update(['fine_amount' => $fine]);
            }

            DB::commit();

            return redirect()->route('checkouts.index')->with('success', 'Book returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkouts.index')->with('error', 'An error occurred while returning the book.');
        }
    }
}
