<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendWhatsAppNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menjadwalkan pengiriman notifikasi tiap bulan
Schedule::call(function () {
    dispatch(new SendWhatsAppNotification());
})->monthlyOn(28, '08:00');

// Command buat testing notifikasi langsung (tidak di tanggal 28 nya)
Schedule::command('report:send')->monthlyOn(28, '08:00');