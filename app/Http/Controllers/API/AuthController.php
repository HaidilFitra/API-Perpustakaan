<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'NamaLengkap' => 'required|string',
            'password' => 'required|min:5',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'NamaLengkap' => $request->NamaLengkap
        ]);

        return response()->json([
            'message' => 'Register Success',
            'token' => JWTAuth::fromUser($user)
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

        return response()->json([
            'message' => 'Login Success',
            'token' => $token
        ])->withCookie(cookie('jwt', $token, 60, 'api/', null, false, true, 'Lax'))
          ->withCookie(cookie('isAuthenticated', 'true', 60));
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logout Success']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Failed to logout'], 500);
        }
    }
    
    public function ForgotPassword(Request $request)
    {
        // 
    }
}
