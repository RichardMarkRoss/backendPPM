<?php
// import the Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebitCardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Don\'t worry, be happy!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // debit cards
    Route::get('/debit-cards', [DebitCardController::class, 'index']);
    Route::post('/debit-cards', [DebitCardController::class, 'store']);
    Route::get('/debit-cards/{debitCard}', [DebitCardController::class, 'show']);
    Route::put('/debit-cards/{debitCard}', [DebitCardController::class, 'update']);
    Route::delete('/debit-cards/{debitCard}', [DebitCardController::class, 'destroy']);
});