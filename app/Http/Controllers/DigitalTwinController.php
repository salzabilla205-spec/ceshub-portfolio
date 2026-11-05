<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DigitalTwinController extends Controller
{
    // 1. Menampilkan Halaman Web Dashboard
    public function index()
    {
        $project = \App\Models\Project::where('status', 'published')->where(function ($query) {
            $query->whereIn('slug', ['digital-twin', 'digital-twin-baterai'])
                ->orWhere('dashboard_url', 'like', '%/dashboard/digital-twin');
        })->with(['teamMembers', 'posts' => fn ($query) => $query->where('status', 'published')->latest('published_at')])->first();
        return view('digital-twin', compact('project'));
    }

    // 2. Menerima Data dari ESP32 (TEM055, PZEM-017, dan BMS Daly)
    public function storeData(Request $request)
    {
        $currentMinute = now()->format('Y-m-d H:i');
        
        $log = DB::connection('jamur')->table('digital_twin_logs')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$currentMinute])
            ->first();

        $dataToSave = [];

        // A. Deteksi data KWh Meter TEM055 (AC 3-Fasa)
        if ($request->has('tegangan_A')) {
            $dataToSave['tegangan_a'] = $request->tegangan_A;
            $dataToSave['arus_a']     = $request->arus_A;
            $dataToSave['tegangan_b'] = $request->tegangan_B;
            $dataToSave['arus_b']     = $request->arus_B;
            $dataToSave['tegangan_c'] = $request->tegangan_C;
            $dataToSave['arus_c']     = $request->arus_C;
        }

        // B. Deteksi data PZEM-017 (DC)
        if ($request->has('daya')) {
            $dataToSave['pzem_tegangan'] = $request->tegangan;
            $dataToSave['pzem_arus']     = $request->arus;
            $dataToSave['pzem_daya']     = $request->daya;
            $dataToSave['pzem_energi']   = $request->energi;
        }

        // C. Deteksi data BMS Daly R24PK (BARU)
        if ($request->has('bms_soc')) {
            $dataToSave['bms_tegangan'] = $request->bms_tegangan;
            $dataToSave['bms_arus']     = $request->bms_arus;
            $dataToSave['bms_soc']      = $request->bms_soc;
            $dataToSave['bms_suhu']     = $request->bms_suhu;
        }

        if (empty($dataToSave)) {
            return response()->json(['status' => 'ignored', 'message' => 'Format data tidak dikenali'], 400);
        }

        // D. Eksekusi Simpan/Gabungkan ke Database 
        if ($log) {
            $dataToSave['updated_at'] = now();
            DB::connection('jamur')->table('digital_twin_logs')->where('id', $log->id)->update($dataToSave);
            return response()->json(['status' => 'updated', 'message' => 'Data berhasil digabungkan ke menit ini']);
        } else {
            $dataToSave['created_at'] = now();
            $dataToSave['updated_at'] = now();
            DB::connection('jamur')->table('digital_twin_logs')->insert($dataToSave);
            return response()->json(['status' => 'created', 'message' => 'Baris data baru berhasil dibuat']);
        }
    }

    // 3. Melempar Data ke Chart (Grafik) di Website
    public function getChartData()
    {
        $data = DB::connection('jamur')->table('digital_twin_logs')
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        return response()->json(['data' => $data]);
    }
}