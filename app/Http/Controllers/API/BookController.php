<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::get();
        return response()->json([
            'books' => $books
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $book = new Book();
        return response()->json([
            'book' => $book
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publisher' => 'required|max:255',
            'year' => 'required|date',
            'image' => 'required|file|mimes:jpg,png,webp',
            'category_id' => 'required|exists:category,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Invalid Fields',
                'errors' => $validate->messages()
            ], 422);
        }elseif (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
            return response()->json([
                'message' => 'You do not have permission to create a book'
            ], 403);
        } else{
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imgName = $image->getClientOriginalName();
            $image->storeAs('images', $imgName,'public');
        }
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'image' => $imgName,
            'category_id' => $request->category_id
        ]);

        return response()->json([
            'book' => $book
        ], 201);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    try {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publisher' => 'required|max:255',
            'year' => 'required|date',
            'image' => 'required|file|mimes:jpg,png,webp',
            'category_id' => 'required|exists:category,id',
        ]);

        if (in_array(Auth::user()->role, ['Admin', 'Staff'])) {
            $book->update($request->all());
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imgName = $image->getClientOriginalName();
                $image->storeAs('images', $imgName,'public');
                $book->image = $imgName;
            }
            return response()->json(['message' => 'Book Successfully Updated', 'book' => $book], 200);
        } else {
            return response()->json(['message' => 'You do not have permission to update this book'], 403);
        }
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Data Not Found'], 404);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::findOrFail($id);
            if (in_array(Auth::user()->role, ['Admin', 'Staff'])) {
                
                if ($book->image) {
                    Storage::disk('public')->delete('images/' . $book->image);
                }
                $book->delete();
                return response()->json(['message' => 'Book Successfully Deleted'], 200);
            } else {
                return response()->json(['message' => 'You do not have permission to delete this book'], 403);
            }
    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Book Not Found'], 404);
    }
    }
}
