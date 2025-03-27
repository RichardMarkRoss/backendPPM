<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    /**
     * Register a new user with a default role
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        $role = User::count() === 0 ? Role::where('name', 'Admin')->first() : Role::where('name', 'User')->first();
    
        if (!$role) {
            return response()->json(['message' => 'No valid role found! Run `php artisan db:seed --class=RolesSeeder`'], 500);
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);
    
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->load('role.permissions'),
            'token' => $token,
        ], 201);
    }
    
    /**
     * Login and generate API token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user = Auth::user()->load('role.permissions');
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Logout and revoke token
     */
    public function logout(Request $request)
    {
        // Revoke all tokens (multi-session logout)
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

    /**
     * Get authenticated user details
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('role.permissions'),
        ]);
    }
}
