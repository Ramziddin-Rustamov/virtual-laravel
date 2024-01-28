<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index()
    {
        $books = Cache::remember('books', now()->addMinutes(1), function () {
            return Book::orderBy('id', 'DESC')->paginate(10);
        });

        return view('book.index', ['books' => $books]);
    }

    public function readerIndex()
    {
        $books = Cache::remember('reader_books', now()->addMinutes(1), function () {
            return Book::orderBy('id', 'DESC')->paginate(48);
        });

        return view('book-home', ['books' => $books]);
    }

    public function readBook($bookread)
    {
        $bookreader = Book::find($bookread);

        if ($bookreader) {
            return view('book-read', ['bookreader' => $bookreader]);
        }

        return back(404);
    }

    public function create()
    {
        $cat = Category::all();
        return view('book.create', ['categories' => $cat]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'author' => ['required'],
            'rating' => ['required'],
            'description' => ['required'],
            'image' => ['required', 'mimes:jpeg,png,jpg,webp'],
        ]);

        // Your store logic here

        Cache::forget('books');

        return redirect()->route('book.index')->with('success', 'A new book added successfully!');
    }

    public function show($book)
    {
        $book = Cache::remember("book_{$book}", now()->addMinutes(10), function () use ($book) {
            return Book::find($book);
        });

        if ($book) {
            return view('book.show', ['book' => $book]);
        }

        return back(404);
    }

    public function edit($book)
    {
        $book = Cache::remember("book_{$book}", now()->addMinutes(10), function () use ($book) {
            return Book::find($book);
        });

        if ($book) {
            return view('book.edit', ['book' => $book]);
        }

        return redirect()->route('book.index')->with('errors', 'Not found');
    }

    public function update(Request $request, $book)
    {
        $book = Book::find($book);

        if ($book) {
            $request->validate([
                'title' => ['required'],
                'author' => ['required'],
                'rating' => ['required'],
                'description' => ['required'],
            ]);

            // Your update logic here

            Cache::forget('books');

            return redirect()->route('book.index')->with('success', 'Book has been updated successfully');
        }
    }

    public function destroy($book)
    {
        $book = Book::find($book);

        if ($book) {
            $file = File::exists(public_path($book->image));
            
            if ($file) {
                File::delete(public_path($book->image));
            }

            $book->delete();

            Cache::forget('books');

            return redirect()->route('book.index')->with('success', 'Book has been deleted');
        }

        return redirect()->route('book.index')->with('errors', 'Book not found');
    }
}
?>