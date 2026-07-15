<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Every minute: send 10-min reminders + auto-complete ended sessions
Schedule::command('bookings:tick')->everyMinute()->withoutOverlapping();
