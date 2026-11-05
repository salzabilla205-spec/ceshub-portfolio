<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Submersible;
use App\Models\SubmersibleConfig;
use App\Models\SubmersibleLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    private $endDate;

    public function __construct()
    {
        $this->endDate = Carbon::parse("2026-04-24 23:59:59");
    }

    function calculateExpPower(?int $lux = 0, ?float $suhu = 0, ?float $daya = 0)
    {
        $lux = $lux ?? 0;
        $suhu = $suhu ?? 0;
        $daya = $daya ?? 0;
    
        $expPower = 0.04363957125 * $lux * (1.0 - 0.0026 * ($suhu - 25.0));
    
        if ($expPower == 0) {
            $deviasi = 0;
            $anomali = 0;
        } else {
            $deviasi = ($daya - $expPower) / $expPower * 100;
            if (abs($deviasi) < 5 || $daya == 0) {
                $anomali = 0;
            } else {
                $anomali = 1;
            }
        }
    
        return [$expPower, $deviasi, $anomali];
    }

    function getLatestData()
    {
        $endDate = $this->endDate;

        $latest = Submersible::where("created_at", "<=", $endDate)->latest()->first();
        if ($latest === null) {
            return null;
        }

        $latest_env = Environment::where("created_at", "<=", $endDate)->latest()->first();
        $max_power = Submersible::where("created_at", "<=", $endDate)->max("daya");

        $latest->v_water = 0.0132 * $latest->debit;
        if ($latest->arus != 0) {
            $latest->status = 1;
        } else {
            $latest->status = 0;
        }

        if ($latest->tegangan != 0) {
            $latest->arus = $latest->daya / $latest->tegangan;
        } else {
            $latest->arus = 0;
        }
        $latest->max_power = $max_power;
        $latest->biaya = $latest->energi_harian * 1.7;
        $latest->emisi = $latest->energi_harian * 0.00085;

        $latest->total_biaya = $latest->energi_total * 1.7;
        $latest->total_emisi = $latest->energi_total * 0.00085;

        $latest->durasi_pemakaian_harian = intdiv($latest->durasi_pemakaian_harian, 60);
        $latest->durasi_pemakaian_total = intdiv($latest->durasi_pemakaian_total, 3600);

        $latest->durasi_koneksi_j = intdiv($latest->durasi_koneksi, 3600);
        $latest->durasi_koneksi_m = intdiv($latest->durasi_koneksi, 60);

        $time_dif = $latest->created_at->diffInSeconds(Carbon::now());
        if ($time_dif > 300) {
            $latest->online_status = "Offline";
        } else {
            $latest->online_status = "Online";
        }

        $status_message = [
            "Offline" => "Koneksi perangkat IoT dan server terputus",
            "Online" => "Perangkat IoT terhubung ke server dengan normal"
        ];

        $latest->status_message = $status_message[$latest->online_status];

        $active_status = SubmersibleConfig::select("id", "active_button")->first();
        $latest->active_status = $active_status?->active_button ?? 0;

        if ($latest->active_status == 0) {
            $latest->active_message_button = "Nyalakan Pompa";
            $latest->active_message_title = "Pompa sedang dimatikan";
            $latest->active_message_subtitle = "Tekan tombol untuk menyalakan pompa";
        } else {
            $latest->active_message_button = "Matikan Pompa";
            $latest->active_message_title = "Pompa sedang diaktifkan";
            $latest->active_message_subtitle = "Tekan tombol untuk mematikan pompa";
        }

        $latest->suhu = $latest_env->suhu ?? 0;
        $latest->suhu_harian = $latest_env->suhu_harian ?? 0;
        $latest->intensitas_cahaya = $latest_env->intensitas_cahaya ?? 0;
        $latest->iradiasi = $latest->intensitas_cahaya ? ($latest->intensitas_cahaya / 116) : 0;

        $expPower = $this->calculateExpPower($latest->intensitas_cahaya, $latest->suhu, $latest->daya);

        $latest->pv_estimasi = $expPower[0];
        $latest->pv_deviasi = $expPower[1];
        $latest->pv_anomali = $expPower[2];

        return $latest;
    }

    function getLogs()
    {
        $logs = SubmersibleLog::select('id', 'active', 'date', 'volume_harian', 'energi_harian', 'durasi_pemakaian_harian', 'suhu_harian')
            ->where('active', 1)
            ->where('date', '<=', $this->endDate->toDateString())
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return $logs;
    }

    public function index()
    {
        $latest = $this->getLatestData();
        if ($latest === null) {
            return view('dashboard.empty', ['cutoff' => $this->endDate]);
        }

        $logs = $this->getLogs();

        $latest->suhu_mingguan = $logs->avg('suhu_harian');

        return view('dashboard', compact('latest', 'logs'));
    }

    public function getDashboardData()
    {
        $latest = $this->getLatestData();

        return response()->json([
            "data" => $latest
        ]);
    }

    public function chartData($data)
    {
        $data_select = [
            "power" => ["daya", "tegangan", "energi_harian", "created_at"],
            "pump" => ["volume_harian", "debit", "created_at"],
            "environment" => ["suhu", "intensitas_cahaya", "created_at"]
        ];

        $endDate = $this->endDate;

        if ($data == "environment") {
            $chart = Environment::select($data_select[$data])
                ->where("created_at", "<=", $endDate)
                ->orderBy("created_at", "asc")
                ->get();
        } else {
            $chart = Submersible::select($data_select[$data])
                ->where("created_at", "<=", $endDate)
                ->orderBy("created_at", "asc")
                ->get();
        }

        return response()->json([
            "data" => $chart
        ]);
    }

    public function heatMapData()
    {
        $cutoff = $this->endDate->toDateString();

        $latestDate = SubmersibleLog::where('date', '<=', $cutoff)->max('date');

        if (!$latestDate) {
            $latestDate = $cutoff;
        }

        $logs = SubmersibleLog::select("suhu_harian", "date", "created_at")
            ->where('date', '<=', $latestDate)
            ->orderBy('date', 'desc')
            ->get()
            ->keyBy('date');

        $heatmapData = [];
        $latestDateObj = \Carbon\Carbon::parse($latestDate);

        for ($i = 15; $i >= 0; $i--) {
            $date = $latestDateObj->copy()->subDays($i)->format('Y-m-d');

            if (isset($logs[$date])) {
                $log = $logs[$date];
                $heatmapData[] = [
                    'suhu_harian' => $log->suhu_harian,
                    'date' => $log->date,
                    'created_at' => $log->created_at,
                    'value' => ($log->suhu_harian - 25) * 10
                ];
            } else {
                $heatmapData[] = [
                    'suhu_harian' => '0',
                    'date' => $date,
                    'created_at' => null,
                    'value' => 0
                ];
            }
        }

        return response()->json([
            "data" => $heatmapData
        ]);
    }

    public function pumpActive($scale = "living-lab")
    {
        if ($scale == "living-lab") {
            $id = 1;
        } else {
            $id = 2;
        }
        $update = SubmersibleConfig::where('id', $id)
            ->update(['active_button' => DB::raw('NOT active_button')]);

        return redirect()->back();
    }
}