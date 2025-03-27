<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Structure;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index()
    {
        $currentUserId = auth()->id();

        $users = User::with('role')->where('id', '!=', $currentUserId)->get();
    
        return response()->json(['users' => $users], 200);
    }
    /**
     * Retrieve a user by ID
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }
    /**
     * Update a user by ID
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('role'),
        ], 200);
    }
    /**
     * Delete a user by ID
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
    /**
     * Register a new user with a default role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);
    
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);
    
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->load('role'),
        ], 201);
    }
    /**
     * Update a user's role by ID
     */
    public function updateUserRole(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->role_id = $validated['role_id'];
        $user->save();

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user->load('role'),
        ], 200);
    }
    /**
     * Retrieve users downstream based on the logged-in user's structure.
     */
    public function downstreamUsers(Request $request)
    {
        $user = Auth::user();

        if (!$user->structure_id) {
            return response()->json(['error' => 'User does not have an assigned structure'], 400);
        }

        $structureIds = $this->getAllDescendantStructureIds($user->structure_id);

        $users = User::whereIn('structure_id', $structureIds)->with('role')->get();

        return response()->json(['downstream_users' => $users], 200);
    }

    /**
     * Recursive method to fetch all descendant structure IDs.
     */
    private function getAllDescendantStructureIds($structureId)
    {
        $ids = [$structureId];
        $childStructures = Structure::where('parent_id', $structureId)->pluck('id')->toArray();

        foreach ($childStructures as $childId) {
            $ids = array_merge($ids, $this->getAllDescendantStructureIds($childId));
        }

        return $ids;
    }
}
