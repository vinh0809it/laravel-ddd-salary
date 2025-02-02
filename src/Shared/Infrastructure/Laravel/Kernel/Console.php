<?php

namespace Src\Shared\Infrastructure\Laravel\Kernel;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Src\User\Infrastructure\Console\Console as UserConsole;
class Console extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        (new UserConsole($this->app, $this->events))->schedule($schedule);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        (new UserConsole($this->app, $this->events))->commands();
    }
   
}
