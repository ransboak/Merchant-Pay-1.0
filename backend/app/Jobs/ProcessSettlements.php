<?php

namespace App\Jobs;

use App\Models\Merchant;
use App\Models\Settlement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSettlements implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting settlement processing job');

        // Get all merchants with positive wallet balance
        $merchants = Merchant::whereHas('wallet', function($query) {
            $query->where('balance', '>', 0);
        })->with('wallet')->get();

        $processedCount = 0;
        $totalAmount = 0;

        foreach ($merchants as $merchant) {
            try {
                $wallet = $merchant->wallet;

                if (!$wallet || $wallet->balance <= 0) {
                    continue;
                }

                $amount = $wallet->balance;

                // Create settlement record
                Settlement::create([
                    'merchant_id' => $merchant->id,
                    'amount' => $amount,
                    'settlement_date' => now(),
                    'reference' => 'SET-' . uniqid()
                ]);

                // Reset wallet balance
                $wallet->balance = 0;
                $wallet->save();

                $processedCount++;
                $totalAmount += $amount;

                Log::info("Settlement processed for merchant {$merchant->id}: {$amount}");
            } catch (\Exception $e) {
                Log::error("Failed to process settlement for merchant {$merchant->id}: " . $e->getMessage());
                // Continue processing other merchants even if one fails
            }
        }

        Log::info("Settlement job completed. Processed {$processedCount} merchants, Total amount: {$totalAmount}");
    }
}
