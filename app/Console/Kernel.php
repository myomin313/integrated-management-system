<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ExchangeRateCommand::class,
        Commands\OTCommand::class,
        Commands\LateRemindCommand::class,
        Commands\CarLicenseExpireCommand::class,
        Commands\EndOTAlertCommand::class,
        Commands\ReceptionistCommand::class,
        Commands\OTRateCommand::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('exchangerate:daily')
        ->daily();
        $schedule->command('lateremind:daily')
        ->daily();
        $schedule->command('endotalert:daily')
        ->daily();
        $schedule->command('ot:monthly')
        ->monthly();
        $schedule->command('receptionist:monthly')
        ->monthly();
        $schedule->command('otrate:monthly')
        ->monthly();
        $schedule->command('carlicenseexpire:daily')
        ->daily();
        $schedule->command('passportexpire:daily')
        ->daily();
         $schedule->command('staypermitexpire:daily')
        ->daily();
         $schedule->command('mjsrvexpire:daily')
        ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
