<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DebitCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * List all transactions for the authenticated user's debit cards.
     */
    public function index()
    {
        $transactions = Transaction::whereHas('debitCard', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return response()->json($transactions);
    }

    /**
     * Store a new transaction (deposit, withdrawal, or purchase).
     */
    public function store(Request $request)
    {
        $request->validate([
            'debit_card_id' => 'required|exists:debit_cards,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:deposit,withdrawal,purchase',
        ]);

        $debitCard = DebitCard::findOrFail($request->debit_card_id);

        if ($debitCard->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($request->type !== 'deposit' && $debitCard->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $transaction = $debitCard->transactions()->create([
            'amount' => $request->amount,
            'type' => $request->type,
            'status' => 'completed',
        ]);
        if ($request->type === 'deposit') {
            $debitCard->increment('balance', $request->amount);
        } else {
            $debitCard->decrement('balance', $request->amount);
        }

        return response()->json([
            'message' => ucfirst($request->type) . ' successful',
            'transaction' => $transaction
        ], 201);
    }

    /**
     * Show details of a specific transaction.
     */
    public function show(Transaction $transaction)
    {
        if ($transaction->debitCard->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($transaction);
    }
}
