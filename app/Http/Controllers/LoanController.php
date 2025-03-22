<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\ScheduledRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * List all loans for authenticated user.
     */
    public function index()
    {
        return response()->json(Auth::user()->loans);
    }

    /**
     * Apply for a new loan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'term' => 'required|in:3,6',
        ]);

        $loan = Auth::user()->loans()->create([
            'amount' => $request->amount,
            'term' => $request->term,
            'remaining_balance' => $request->amount,
            'status' => 'active'
        ]);

        $installmentAmount = $request->amount / $request->term;
        for ($i = 1; $i <= $request->term; $i++) {
            ScheduledRepayment::create([
                'loan_id' => $loan->id,
                'due_date' => Carbon::now()->addMonths($i),
                'amount_due' => $installmentAmount,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Loan approved',
            'loan' => $loan
        ], 201);
    }

    /**
     * View a specific loan.
     */
    public function show(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($loan);
    }
}