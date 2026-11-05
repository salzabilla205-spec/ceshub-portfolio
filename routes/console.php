<?php

use App\Models\Submersible;
use App\Models\SubmersibleLog;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('submersible:daily-log', function () {

    $today = Submersible::whereDate('created_at', Carbon::today())
        ->latest()
        ->first();


    if (!$today) {
        $this->warn('Tidak ada data hari ini');
        return;
    }

    if ($today->volume > 1) {
        $activeStatus = 1;
    } else {
        $activeStatus = 0;
    }

    SubmersibleLog::create([
        'suhu_harian' => $today->suhu_harian,
        'durasi_pemakaian_harian' => $today->durasi_pemakaian_harian,
        'volume_harian' => $today->volume_harian,
        'energi_harian' => $today->energi_harian,
        'active' => $activeStatus,
        'date' => Carbon::today(),
    ]);

    $this->info('Log harian submersible berhasil disimpan');
})->purpose('Simpan log harian submersible');


/*
|--------------------------------------------------------------------------
| Task Scheduling
|--------------------------------------------------------------------------
*/

return function (Schedule $schedule) {
    $schedule->command('submersible:daily-log')
        ->dailyAt('23:55');
};
