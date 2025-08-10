<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $books = Book::query()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('library.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('library.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:books,isbn',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'quantity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['available_quantity'] = $request->quantity;

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Book added to catalog successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): View
    {
        $book->load(['checkouts.student', 'checkouts.checkedOutBy', 'checkouts.checkedInBy']);
        return view('library.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book): View
    {
        return view('library.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'quantity' => 'required|integer|min:1',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        if ($book->checkouts()->whereNull('returned_date')->exists()) {
            return redirect()->route('books.index')->with('error', 'Cannot delete a book with active checkouts.');
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
