<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReceivedRepaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Don\'t worry, be happy!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Protected Authentication Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Debit Cards
    Route::apiResource('debit-cards', DebitCardController::class);

    // Transactions
    Route::apiResource('transactions', TransactionController::class);

    // Loans
    Route::apiResource('loans', LoanController::class);

    // Loan Repayments
    Route::get('/loans/{loan}/repayments', [ReceivedRepaymentController::class, 'getRepaymentSchedule']);
    Route::post('/loans/{loan}/repayments', [ReceivedRepaymentController::class, 'makeRepayment']);

    // User Management
    Route::apiResource('users', UserController::class);
    Route::put('/users/{user}/role', [UserController::class, 'updateUserRole']);
    
    // Role, Permission, and Structure Management (Now Open to Authenticated Users)
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('structures', StructureController::class);
});
