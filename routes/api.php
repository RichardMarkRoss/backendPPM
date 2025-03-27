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

    // Debit Card Routes
    Route::prefix('debit-cards')->group(function () {
        Route::get('/', [DebitCardController::class, 'index']);
        Route::post('/', [DebitCardController::class, 'store']);
        Route::get('/{debitCard}', [DebitCardController::class, 'show']);
        Route::put('/{debitCard}', [DebitCardController::class, 'update']);
        Route::delete('/{debitCard}', [DebitCardController::class, 'destroy']);
    });

    // Transaction Routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/{transaction}', [TransactionController::class, 'show']);
    });

    // Loan Routes
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoanController::class, 'index']);
        Route::post('/', [LoanController::class, 'store']);
        Route::get('/{loan}', [LoanController::class, 'show']);

        // Repayments under Loans
        Route::get('/{loan}/repayments', [ReceivedRepaymentController::class, 'getRepaymentSchedule']);
        Route::post('/{loan}/repayments', [ReceivedRepaymentController::class, 'makeRepayment']);
    });

    // User Management Routes
    Route::prefix('users')->group(function () {
        Route::get('/downstream', [UserController::class, 'downstreamUsers']); // Get users under current user's structure
        Route::get('/', [UserController::class, 'index'])->middleware('can:manage-users');
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}/role', [UserController::class, 'updateRole'])->middleware('can:manage-users');
    });

    // Admin-Only Routes (Roles, Permissions, Structures)
    Route::middleware('can:manage-roles')->group(function () {
        Route::apiResource('roles', RoleController::class);
    });

    Route::middleware('can:manage-permissions')->group(function () {
        Route::apiResource('permissions', PermissionController::class);
    });

    Route::middleware('can:manage-structures')->group(function () {
        Route::apiResource('structures', StructureController::class);
    });
});
