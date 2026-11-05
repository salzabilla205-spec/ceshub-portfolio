<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HessTelemetry;
use Symfony\Component\HttpFoundation\StreamedResponse;

class hessDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard HESS
     */
    public function index()
    {
        $project = \App\Models\Project::where('status', 'published')->where(function ($query) {
            $query->whereIn('slug', ['hess', 'hess-riset'])->orWhere('dashboard_url', 'like', '%/dashboard/hess');
        })->with(['teamMembers', 'posts' => fn ($query) => $query->where('status', 'published')->latest('published_at')])->first();
        return view('hess', compact('project'));
    }

    /**
     * Menerima kiriman telemetri dari ESP32
     */
    public function storeTelemetry(Request $request)
    {
        if ($request->input('api_key') !== 'secret_hess_12345') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $telemetry = HessTelemetry::create([
            'speed'     => $request->input('speed', 0),
            'rpm'       => $request->input('rpm', 0),
            'v_bat'     => $request->input('v_bat', 0),
            'i_bat'     => $request->input('i_bat', 0),
            'p_bat'     => $request->input('p_bat', 0),
            'soc_bat'   => $request->input('soc_bat', 0),
            'wh_bat'    => $request->input('wh_bat', 0),
            'v_kap'     => $request->input('v_kap', 0),
            'i_kap'     => $request->input('i_kap', 0),
            'p_kap'     => $request->input('p_kap', 0),
            'soc_kap'   => $request->input('soc_kap', 0),
            'wh_kap'    => $request->input('wh_kap', 0),
            'v_batkap'  => $request->input('v_batkap', 0),
            'i_batkap'  => $request->input('i_batkap', 0),
            'wh_batkap' => $request->input('wh_batkap', 0),
            'buck'      => filter_var($request->input('buck'), FILTER_VALIDATE_BOOLEAN),
            'boost'     => filter_var($request->input('boost'), FILTER_VALIDATE_BOOLEAN),
        ]);

        return response()->json(['message' => 'Data recorded successfully', 'data' => $telemetry], 200);
    }

    /**
     * Mengambil data terbaru untuk pembaruan realtime di Frontend (JSON)
     */
    public function getDashboardData()
    {
        $latest = HessTelemetry::latest()->first();

        return response()->json([
            'speed'     => number_format($latest->speed ?? 0, 1),
            'rpm'       => number_format($latest->rpm ?? 0, 0),
            'v_bat'     => number_format($latest->v_bat ?? 0, 2),
            'i_bat'     => number_format($latest->i_bat ?? 0, 2),
            'p_bat'     => number_format($latest->p_bat ?? 0, 2),
            'soc_bat'   => number_format($latest->soc_bat ?? 0, 1),
            'wh_bat'    => number_format($latest->wh_bat ?? 0, 2),
            'v_kap'     => number_format($latest->v_kap ?? 0, 2),
            'i_kap'     => number_format($latest->i_kap ?? 0, 2),
            'p_kap'     => number_format($latest->p_kap ?? 0, 2),
            'soc_kap'   => number_format($latest->soc_kap ?? 0, 1),
            'wh_kap'    => number_format($latest->wh_kap ?? 0, 2),
            'v_batkap'  => number_format($latest->v_batkap ?? 0, 2),
            'i_batkap'  => number_format($latest->i_batkap ?? 0, 2),
            'wh_batkap' => number_format($latest->wh_batkap ?? 0, 2),
            'buck'      => (bool)($latest->buck ?? false),
            'boost'     => (bool)($latest->boost ?? false),
            'updated'   => $latest ? $latest->created_at->format('H:i:s') : '-',
            'timestamp' => $latest ? $latest->created_at->timestamp : 0,
        ]);
    }
    
    /**
     * Mengambil data historis untuk semua chart HESS (dipanggil JS setiap 3 detik)
     */
    public function chartAll()
    {
        $logs = HessTelemetry::orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->reverse()
            ->values();
    
        return response()->json([
            'labels'  => $logs->pluck('created_at')->map(fn($t) => $t->format('H:i:s')),
            'v_bat'   => $logs->pluck('v_bat'),
            'i_bat'   => $logs->pluck('i_bat'),
            'soc_bat' => $logs->pluck('soc_bat'),
            'v_kap'   => $logs->pluck('v_kap'),
            'i_kap'   => $logs->pluck('i_kap'),
            'soc_kap' => $logs->pluck('soc_kap'),
            'speed'   => $logs->pluck('speed'),
            'rpm'     => $logs->pluck('rpm'),
        ]);
    }

    /**
     * Export data telemetri HESS ke format CSV dengan filter tanggal & jam
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'hess_telemetry_' . date('Y-m-d_H-i-s') . '.csv';
        $query = HessTelemetry::query();

        // Filter rentang tanggal & jam (start_date, end_date, start_time, end_time)
        if ($request->filled('start_date')) {
            $startDate = $request->input('start_date');
            $endDate   = $request->input('end_date', $startDate);
            $startTime = $request->input('start_time', '00:00:00');
            $endTime   = $request->input('end_time', '23:59:59');

            $fullStart = $startDate . ' ' . $startTime;
            $fullEnd   = $endDate . ' ' . $endTime;

            $query->whereBetween('created_at', [$fullStart, $fullEnd]);
        } 
        // Filter jika cuma kirim single tanggal (date) + jam spesifik
        elseif ($request->filled('date')) {
            $date      = $request->input('date');
            $startTime = $request->input('start_time', '00:00:00');
            $endTime   = $request->input('end_time', '23:59:59');

            $fullStart = $date . ' ' . $startTime;
            $fullEnd   = $date . ' ' . $endTime;

            $query->whereBetween('created_at', [$fullStart, $fullEnd]);
        }
        // Bawaan default kalau tidak diisi: Data Hari Ini Saja
        else {
            $query->whereDate('created_at', now()->toDateString());
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID', 'Waktu', 'Speed (km/h)', 'RPM', 
            'V_Bat (V)', 'I_Bat (A)', 'P_Bat (W)', 'SOC_Bat (%)', 'Wh_Bat', 
            'V_Kap (V)', 'I_Kap (A)', 'P_Kap (W)', 'SOC_Kap (%)', 'Wh_Kap', 
            'V_BatKap (V)', 'I_BatKap (A)', 'Wh_BatKap', 
            'Buck', 'Boost'
        ];

        $callback = function() use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->created_at,
                    $row->speed,
                    $row->rpm,
                    $row->v_bat,
                    $row->i_bat,
                    $row->p_bat,
                    $row->soc_bat,
                    $row->wh_bat,
                    $row->v_kap,
                    $row->i_kap,
                    $row->p_kap,
                    $row->soc_kap,
                    $row->wh_kap,
                    $row->v_batkap,
                    $row->i_batkap,
                    $row->wh_batkap,
                    $row->buck ? '1' : '0',
                    $row->boost ? '1' : '0',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}