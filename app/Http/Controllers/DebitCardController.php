<?php

namespace App\Http\Controllers;

use App\Models\DebitCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebitCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Auth::user()->debitCards);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'card_number' => 'required|string|unique:debit_cards',
            'balance' => 'numeric|min:0',
            'status' => 'in:active,blocked,expired',
        ]);

        $debitCard = Auth::user()->debitCards()->create([
            'card_number' => $request->card_number,
            'balance' => $request->balance ?? 0.00,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json([
            'message' => 'Debit card created successfully',
            'debit_card' => $debitCard
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DebitCard $debitCard)
    {
        if ($debitCard->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($debitCard);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DebitCard $debitCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DebitCard $debitCard)
    {
        if ($debitCard->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'balance' => 'numeric|min:0',
            'status' => 'in:active,blocked,expired',
        ]);

        $debitCard->update($request->only(['balance', 'status']));

        return response()->json([
            'message' => 'Debit card updated successfully',
            'debit_card' => $debitCard
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DebitCard $debitCard)
    {
        if ($debitCard->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $debitCard->delete();

        return response()->json(['message' => 'Debit card deleted successfully']);
    }
}
