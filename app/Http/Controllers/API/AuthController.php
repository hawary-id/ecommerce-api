<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|unique:users',
                'password' => 'required|min:6',
            ]);
    
            DB::beginTransaction();
    
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
    
            if (!$user || !$user->exists) {
                throw new Exception('Failed to create user.');
            }
    
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->accessToken;
    
            DB::commit();
    
            return response()->json([
                'id' => $user->id,
                'username' => $user->username,
                'token' => $token,
            ])->cookie('auth_token', $token, 60);
    
        } catch (ValidationException $e) {
            DB::rollback();
            Log::error('Validation error during registration: ' . $e->getMessage());
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
    
            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            $user = Auth::user();
    
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->accessToken;
    
            return response()->json([
                'id' => $user->id,
                'username' => $user->username,
                'token' => $token,
            ])->cookie('auth_token', $token, 60);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['error' => 'Login failed'], 500);
        }
    }
}
