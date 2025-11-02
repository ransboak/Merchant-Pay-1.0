<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerchantRequest;
use App\Http\Requests\UpdateMerchantStatusRequest;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        $merchants = Merchant::with('wallet')->get();
        return response()->json($merchants);
    }

    public function store(StoreMerchantRequest $request)
    {
        $merchant = Merchant::create($request->validated());
        $merchant->wallet()->create(['balance' => 0]);

        return response()->json([
            'success' => true,
            'data' => $merchant,
            'message' => 'Merchant created successfully.'
        ], 201);
    }

    public function updateStatus(UpdateMerchantStatusRequest $request, Merchant $merchant)
    {
        $merchant->is_active = $request->is_active;
        $merchant->save();

        return response()->json([
            'success' => true,
            'data' => $merchant,
            'message' => 'Merchant status updated.'
        ]);
    }
}
