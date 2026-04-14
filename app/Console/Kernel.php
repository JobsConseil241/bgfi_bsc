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
        // Import des comptes BGFI toutes les heures entre 12h et 16h
        $schedule->command('bgfi:import')->hourly()->between('12:00', '16:00');

        // Import supplémentaire à 7h, 9h et 13h
        $schedule->command('bgfi:import')->dailyAt('07:00');
        $schedule->command('bgfi:import')->dailyAt('09:00');
        $schedule->command('bgfi:import')->dailyAt('13:00');
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
