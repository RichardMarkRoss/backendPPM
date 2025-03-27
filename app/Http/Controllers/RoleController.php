<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Get all roles with their permissions and child roles.
     */
    public function index()
    {
        return Role::with('permissions', 'childRoles')->withCount('users')->get();
    }

    /**
     * Store a new role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'parent_role_id' => 'nullable|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'parent_role_id' => $validated['parent_role_id'] ?? null,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        return response()->json($role->load('permissions'), 201);
    }

    /**
     * Get a specific role with related permissions and child roles.
     */
    public function show(Role $role)
    {
        return $role->load('permissions', 'childRoles', 'users');
    }

    /**
     * Update a role's details.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:roles,name,' . $role->id,
            'parent_role_id' => 'nullable|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'] ?? $role->name,
            'parent_role_id' => $validated['parent_role_id'] ?? $role->parent_role_id,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json($role->load('permissions'));
    }

    /**
     * Delete a role safely by reassigning child roles or preventing deletion if still in use.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            throw ValidationException::withMessages(['role' => 'Cannot delete a role assigned to users.']);
        }

        // Reassign child roles to parent role before deleting
        if ($role->childRoles()->count() > 0) {
            $role->childRoles()->update(['parent_role_id' => $role->parent_role_id]);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
