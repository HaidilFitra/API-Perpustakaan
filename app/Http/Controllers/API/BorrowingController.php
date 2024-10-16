<?php

namespace App\Http\Controllers\API;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Borrowing = Borrowing::all();
        return response()->json([
            'Borrowing' => $Borrowing
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Borrowing = new Borrowing();
        return response()->json([
            'Borrowing' => $Borrowing
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'buku_id' => 'required|exists:book,id',
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Invalid Fields',
                'errors' => $validate->messages()
            ], 422);
        } elseif (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
            return response()->json([
                'message' => 'You do not have permission to create a Borrowing'
            ], 403);
        }

        $Borrowing = Borrowing::create($request->all());
        return response()->json([
            'message' => 'Borrowing Successfully Created',
            'Borrowing' => $Borrowing
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Borrowing = Borrowing::findOrFail($id);
        return response()->json([
            'Borrowing' => $Borrowing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Borrowing = Borrowing::findOrFail($id);
        return response()->json([
            'Borrowing' => $Borrowing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       try{
        $Borrowing = Borrowing::findOrFail($id);
        if (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
            return response()->json([
                'message' => 'You do not have permission to update this Borrowing'
            ], 403);
        } else {
            $Borrowing->update($request->all());
            return response()->json([
                'message' => 'Borrowing Successfully Updated',
                'Borrowing' => $Borrowing
            ], 200);
        }
       } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Borrowing Not Found',
        ]);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $Borrowing = Borrowing::findOrFail($id);
            if (!in_array(Auth::user()->role, ['Admin', 'Staff'])) {
                return response()->json([
                    'message' => 'You do not have permission to delete this Borrowing'
                ], 403);
            } else {
                $Borrowing->delete();
                return response()->json([
                    'message' => 'Borrowing Successfully Deleted',
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Borrowing Not Found',
            ]);
        }
    }
}
