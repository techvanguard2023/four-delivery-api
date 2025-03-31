<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Artisan::call('reservations:cancel-expired');
})->everyMinute();
