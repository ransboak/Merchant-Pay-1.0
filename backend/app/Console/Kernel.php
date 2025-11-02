<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run settlement processing daily at 2:00 AM
        $schedule->job(new \App\Jobs\ProcessSettlements)
                 ->dailyAt('02:00')
                 ->timezone('UTC')
                 ->withoutOverlapping()
                 ->onFailure(function () {
                     \Log::error('Scheduled settlement job failed');
                 })
                 ->onSuccess(function () {
                     \Log::info('Scheduled settlement job completed successfully');
                 });

        // Alternative: Run weekly on Mondays at 3:00 AM (commented out)
        // $schedule->job(new \App\Jobs\ProcessSettlements)
        //          ->weeklyOn(1, '03:00')
        //          ->timezone('UTC')
        //          ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
