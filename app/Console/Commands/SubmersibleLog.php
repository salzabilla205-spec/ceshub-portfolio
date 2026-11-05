<?php

namespace App\Console\Commands;

use App\Models\Submersible;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubmersibleLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:submersible-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public static function handle()
    {
        $today = Submersible::select('id', 'suhu_harian', 'durasi_pemakaian_harian', 'volume_harian', 'energi_harian', 'created_at')
            ->whereDate('created_at', Carbon::today())
            ->latest()->first();

        if (!empty($today)) {
            $log = SubmersibleLog::create([
                "suhu_harian" => $today->suhu_harian,
                "durasi_pemakaian_harian" => $today->durasi_pemakaian_harian,
                "volume_harian" => $today->volume_harian,
                "energi_harian" => $today->energi_harian,
                "active" => 1,
                "date" => Carbon::today(),
            ]);

            return response()->json($log);
        } else {
            return response()->json([
                "Message" => "Tidak ada data hari ini",
            ]);
        }
    }
}
