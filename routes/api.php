<?php
// import the Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReceivedRepaymentController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Don\'t worry, be happy!']);
});

// user authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // user authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // debit cards
    Route::get('/debit-cards', [DebitCardController::class, 'index']);
    Route::post('/debit-cards', [DebitCardController::class, 'store']);
    Route::get('/debit-cards/{debitCard}', [DebitCardController::class, 'show']);
    Route::put('/debit-cards/{debitCard}', [DebitCardController::class, 'update']);
    Route::delete('/debit-cards/{debitCard}', [DebitCardController::class, 'destroy']);

    // transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);

    //loan
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/{loan}', [LoanController::class, 'show']);

    //repayments
    Route::get('/loans/{loan}/repayments', [ReceivedRepaymentController::class, 'getRepaymentSchedule']);
    Route::post('/loans/{loan}/repayments', [ReceivedRepaymentController::class, 'makeRepayment']);
});