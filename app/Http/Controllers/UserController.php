<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Structure;

class UserController extends Controller
{
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
