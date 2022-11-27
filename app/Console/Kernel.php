<?php

namespace App\Console;

use App\Cron\SessionNoticeCron;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Application;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    public function __construct(Application $app, private SessionNoticeCron $sessionNoticeCron)
    {
        parent::__construct($app);
    }

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            try {
                echo $this->sessionNoticeCron->noticeSessions();
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        })->everyMinute();
    }
}
