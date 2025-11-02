<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Settlement;
use App\Jobs\ProcessSettlements;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    // public function index() {
    //     return Settlement::with('merchant')->get();
    // }

    public function index(Request $request)
    {
        $query = Settlement::with('merchant');

        if ($request->merchant_id) {
            $query->where('merchant_id', $request->merchant_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('settlement_date', [$request->start_date, $request->end_date]);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    public function runSettlement() {
        // Dispatch the settlement job to process in the background
        ProcessSettlements::dispatch();

        return response()->json([
            'success' => true,
            'message' => 'Settlement job has been queued and will be processed shortly.'
        ]);
    }
}
