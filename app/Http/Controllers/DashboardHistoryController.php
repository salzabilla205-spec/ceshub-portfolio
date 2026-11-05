<?php

namespace App\Http\Controllers;

use App\Models\HessTelemetry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DashboardHistoryController extends Controller
{
    public function readings(Request $request, string $project)
    {
        abort_unless(in_array($project, ['hess', 'digital-twin'], true), 404);
        $request->validate([
            'from' => ['required', 'date_format:Y-m-d\TH:i'],
            'to' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:from'],
            'page' => ['nullable', 'integer', 'min:1'],
            'all' => ['nullable', 'boolean'],
            'type' => ['nullable', 'in:ac,dc,bms'],
        ]);
        $from = Carbon::parse($request->input('from'));
        $to = Carbon::parse($request->input('to'))->endOfMinute();
        $fields = $project === 'hess' ? [
            'v_bat' => 'Tegangan baterai (V)', 'i_bat' => 'Arus baterai (A)',
            'p_bat' => 'Daya baterai (W)', 'soc_bat' => 'SOC baterai (%)', 'wh_bat' => 'Energi baterai (Wh)',
            'v_kap' => 'Tegangan superkapasitor (V)', 'i_kap' => 'Arus superkapasitor (A)',
            'p_kap' => 'Daya superkapasitor (W)', 'soc_kap' => 'SOC superkapasitor (%)', 'wh_kap' => 'Energi superkapasitor (Wh)',
            'v_batkap' => 'Tegangan sistem (V)', 'i_batkap' => 'Arus sistem (A)', 'wh_batkap' => 'Energi sistem (Wh)',
            'speed' => 'Kecepatan (km/h)', 'rpm' => 'Putaran (RPM)', 'buck' => 'BUCK', 'boost' => 'BOOST',
        ] : match ($request->input('type', 'bms')) {
            'ac' => ['tegangan_a'=>'VA (V)', 'tegangan_b'=>'VB (V)', 'tegangan_c'=>'VC (V)', 'arus_a'=>'IA (A)', 'arus_b'=>'IB (A)', 'arus_c'=>'IC (A)'],
            'dc' => ['pzem_tegangan'=>'Tegangan (V)', 'pzem_arus'=>'Arus (A)', 'pzem_daya'=>'Daya (W)', 'pzem_energi'=>'Energi (Wh)'],
            default => ['bms_tegangan'=>'Tegangan (V)', 'bms_arus'=>'Arus (A)', 'bms_soc'=>'SOC (%)', 'bms_suhu'=>'Suhu (°C)'],
        };
        try {
            $query = $project === 'hess' ? HessTelemetry::query() : DB::connection('jamur')->table('digital_twin_logs');
            $query->whereBetween('created_at', [$from, $to]);
            $total = (clone $query)->count();
            if ($request->boolean('all') && $total > 10000) {
                return response()->json(['message'=>'Rentang melebihi 10.000 pembacaan. Pilih rentang lebih pendek.'], 422);
            }
            $query->orderBy('created_at')->orderBy('id');
            if (!$request->boolean('all')) $query->skip(($request->integer('page', 1)-1)*25)->take(25);
            $rows = $query->get(['created_at', ...array_keys($fields)])->map(function ($row) use ($fields) {
                return [Carbon::parse($row->created_at)->format('d/m/Y H:i:s'), ...array_map(function ($key) use ($row) {
                    $value = $row->$key;
                    return in_array($key, ['buck','boost'], true) ? ($value === null ? null : ($value ? 'ON' : 'OFF')) : $value;
                }, array_keys($fields))];
            });
        } catch (QueryException $exception) {
            report($exception);
            return response()->json(['message'=>'Riwayat pembacaan belum tersedia.', 'headers'=>['Waktu', ...array_values($fields)], 'rows'=>[], 'total'=>0], 503);
        }
        return response()->json([
            'title'=>$project === 'hess' ? 'HESS' : 'DIGITAL TWIN BATERAI · '.strtoupper($request->input('type','bms')),
            'period'=>$from->format('d/m/Y H:i').' – '.$to->format('d/m/Y H:i').' ('.config('app.timezone').')',
            'headers'=>['Waktu', ...array_values($fields)], 'rows'=>$rows, 'total'=>$total,
            'page'=>$request->integer('page',1), 'last_page'=>max(1,(int)ceil($total/25)),
        ]);
    }
}
