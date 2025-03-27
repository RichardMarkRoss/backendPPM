<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StructureController extends Controller
{
    /**
     * Get all structures with their child structures.
     */
    public function index()
    {
        return Structure::with('childStructures')->withCount('users')->get();
    }

    /**
     * Store a new structure.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:structures,name',
            'parent_structure_id' => 'nullable|exists:structures,id',
        ]);

        $structure = Structure::create($validated);

        return response()->json($structure->load('childStructures'), 201);
    }

    /**
     * Get a specific structure with related child structures.
     */
    public function show(Structure $structure)
    {
        return $structure->load('childStructures', 'users');
    }

    /**
     * Update a structure's details.
     */
    public function update(Request $request, Structure $structure)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:structures,name,' . $structure->id,
            'parent_structure_id' => 'nullable|exists:structures,id',
        ]);

        $structure->update($validated);

        return response()->json($structure->load('childStructures'));
    }

    /**
     * Delete a structure safely by reassigning child structures or preventing deletion if still in use.
     */
    public function destroy(Structure $structure)
    {
        if ($structure->users()->count() > 0) {
            throw ValidationException::withMessages(['structure' => 'Cannot delete a structure assigned to users.']);
        }

        // Reassign child structures to parent structure before deleting
        if ($structure->childStructures()->count() > 0) {
            $structure->childStructures()->update(['parent_structure_id' => $structure->parent_structure_id]);
        }

        $structure->delete();

        return response()->json(['message' => 'Structure deleted successfully']);
    }
}
