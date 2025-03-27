<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * Get all permissions with their assigned roles.
     */
    public function index()
    {
        return Permission::with('roles')->get();
    }

    /**
     * Store a new permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    /**
     * Show a specific permission with roles.
     */
    public function show(Permission $permission)
    {
        return response()->json($permission->load('roles'));
    }

    /**
     * Update an existing permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }

    /**
     * Delete a permission (only if not assigned to any role).
     */
    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            throw ValidationException::withMessages(['permission' => 'Cannot delete a permission assigned to roles.']);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
