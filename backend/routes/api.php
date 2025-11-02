<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\TransactionController;
use App\Models\Merchant;
use App\Models\Settlement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return response()->json(['success' => true, 'message' => 'API is working']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/reports/summary', function () {
        $totalMerchants = Merchant::count();
        $totalTransactions = Transaction::count();
        $totalSettlements = Settlement::count();
        $totalFees = Transaction::sum('fee'); // change field name if needed

        return response()->json([
            'data' => [
                'merchants' => $totalMerchants,
                'transactions' => $totalTransactions,
                'settlements' => $totalSettlements,
                'fees' => $totalFees,
            ]
        ]);
    });

    // Merchant, Transaction, Settlement routes go here
    Route::get('merchants', [MerchantController::class, 'index']);
    Route::post('merchants', [MerchantController::class, 'store']);
    Route::patch('merchants/{merchant}/status', [MerchantController::class, 'updateStatus']);

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/fees', [TransactionController::class, 'totalFees']);

    Route::get('settlements', [SettlementController::class, 'index']);
    Route::post('settlements/run', [SettlementController::class, 'runSettlement']);
});

// Route::get('merchants',[MerchantController::class,'index']);
// Route::post('merchants',[MerchantController::class,'store']);
// Route::patch('merchants/{merchant}/status',[MerchantController::class,'updateStatus']);

// Route::get('transactions',[TransactionController::class,'index']);
// Route::post('transactions',[TransactionController::class,'store']);

// Route::get('settlements',[SettlementController::class,'index']);
// // Route::get('settlements', [SettlementController::class, 'show']);

// Route::post('settlements/run',[SettlementController::class,'runSettlement']);

// Route::get('transactions', [TransactionController::class, 'index']);
// Route::get('transactions/fees', [TransactionController::class, 'totalFees']);
