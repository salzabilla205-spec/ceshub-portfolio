<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LabSubmersible extends Model
{
    //
    protected $table = 'lab_submersible_data';

    protected $fillable = [
        'lampu1',
        'lampu2',
        'tegangan',
        'daya',
        'energi_harian',
        'energi_total',

        'durasi_pemakaian_harian',
        'durasi_pemakaian_total',

        'intensitas_cahaya',
        'irradiansi',
        'suhu',
        'suhu_harian',
        'durasi_koneksi'
    ];

    // BH1750 solar-light estimate using the user's factor, not a calibrated pyranometer.
    public const LUX_PER_WM2 = 120.0;

    public function getEstimasiIrradiansiAttribute(): float
    {
        $reported = $this->getAttribute('irradiansi');
        return round($reported !== null
            ? (float) $reported
            : (float) $this->intensitas_cahaya / self::LUX_PER_WM2, 2);
    }

    public static function getTodayData()
    {
        return self::select('energi_harian', 'suhu_harian', 'durasi_pemakaian_harian', 'durasi_koneksi', 'id', 'created_at')
            ->whereDate('created_at', Carbon::today())
            ->latest()->first();
    }

    public static function getLatestData()
    {
        return self::select('energi_total', 'durasi_pemakaian_total', 'id', 'created_at')
            ->latest()->first();
    }

    public static function todayMax($day, $coloumn)
    {
        return self::whereDate('created_at', $day)->max($coloumn);
    }

    public static function todayDataCount()
    {
        return self::whereDate('created_at', Carbon::today())
            ->count();
    }
}
