<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Merchant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // public function index()
    // {
    //     return Transaction::with('merchant')->get();
    // }

    // public function store(Request $request) {
    //     $request->validate([
    //         'merchant_id'=>'required|exists:merchants,id',
    //         'amount'=>'required|numeric|min:1',
    //         'payment_reference'=>'required|unique:transactions'
    //     ]);

    //     $merchant = Merchant::find($request->merchant_id);
    //     $wallet = $merchant->wallet;

    //     $fee = $request->amount * 0.015;
    //     $netAmount = $request->amount - $fee;

    //     $transaction = Transaction::create([
    //         'merchant_id'=>$merchant->id,
    //         'amount'=>$request->amount,
    //         'payment_reference'=>$request->payment_reference,
    //         'status'=>'successful'
    //     ]);

    //     $wallet->balance += $netAmount;
    //     $wallet->save();

    //     return response()->json($transaction);
    // }
    public function index(Request $request)
    {
        $query = Transaction::with('merchant');

        if ($request->merchant_id) {
            $query->where('merchant_id', $request->merchant_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    // Calculate total fees earned
    public function totalFees(Request $request)
    {
        $query = Transaction::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Platform fee is 1.5%
        $totalFees = $query->sum(DB::raw('amount * 0.015'));

        return response()->json([
            'success' => true,
            'total_fees' => $totalFees,
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            $merchant = Merchant::findOrFail($request->merchant_id);
            $wallet = $merchant->wallet;

            $fee = $request->amount * 0.015;
            $netAmount = $request->amount - $fee;

            $transaction = Transaction::create([
                'merchant_id' => $merchant->id,
                'amount' => $request->amount,
                'fee' => $fee,
                'payment_reference' => $request->payment_reference,
                'status' => 'successful'
            ]);

            $wallet->balance += $netAmount;
            $wallet->save();

            return response()->json([
                'success' => true,
                'data' => $transaction,
                'message' => 'Transaction processed successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
