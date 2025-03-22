<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\ReceivedRepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivedRepaymentController extends Controller
{
    /**
     * Get repayment schedule for a loan.
     */
    public function getRepaymentSchedule($loanId)
    {
        $loan = Loan::where('id', $loanId)
            ->where('user_id', Auth::id())
            ->with('scheduledRepayments')
            ->first();

        if (!$loan) {
            return response()->json(['message' => 'Loan not found or unauthorized'], 403);
        }

        return response()->json($loan->scheduledRepayments);
    }

    /**
     * Make a loan repayment.
     */
    public function makeRepayment(Request $request, $loanId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $loan = Loan::where('id', $loanId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$loan) {
            return response()->json(['message' => 'Loan not found or unauthorized'], 403);
        }

        if ($loan->remaining_balance <= 0) {
            return response()->json(['message' => 'Loan already paid'], 400);
        }

        // Process repayment
        $amountPaid = min($request->amount, $loan->remaining_balance);
        ReceivedRepayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => $amountPaid,
            'paid_at' => now(),
        ]);

        // Update remaining balance
        $loan->decrement('remaining_balance', $amountPaid);

        // Mark scheduled repayments as paid if they are fully covered
        $scheduledRepayments = $loan->scheduledRepayments()->where('status', 'pending')->get();
        foreach ($scheduledRepayments as $repayment) {
            if ($amountPaid >= $repayment->amount_due) {
                $repayment->update(['status' => 'paid']);
                $amountPaid -= $repayment->amount_due;
            }
        }

        // Mark loan as paid if balance is 0
        if ($loan->remaining_balance <= 0) {
            $loan->update(['status' => 'paid']);
        }

        return response()->json([
            'message' => 'Repayment successful',
            'remaining_balance' => $loan->remaining_balance,
        ]);
    }
}
