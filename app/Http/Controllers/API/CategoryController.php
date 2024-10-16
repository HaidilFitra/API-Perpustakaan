<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new Category();
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Invalid Field',
                'errors' => $validate->messages()
            ], 422);
        } elseif (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
            return response()->json([
                'message' => 'You do not have permission to create a category'
            ], 403);
        } else {
            $category = Category::create($request->all());
            return response()->json([
                'message' => 'Category Successfully Created',
                'category' => $category
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                $category
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category Not Found',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $request->validate([
                'Name' => 'required|max:255',
            ]);
            $category = Category::findOrFail($id);
            if (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
                return response()->json([
                    'message' => 'You do not have permission to update this category'
                ], 403);
            } else {
                $category->update($request->all());
                return response()->json([
                    'message' => 'Category Successfully Updated',
                    'category' => $category
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category Not Found',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            if (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
                return response()->json([
                    'message' => 'You do not have permission to delete this category'
                ], 403);
            } else {
                $category->delete();
                return response()->json([
                    'message' => 'Category Successfully Deleted',
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category Not Found',
            ]);
        }
    }
}
