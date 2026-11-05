<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Submersible extends Model
{
    //
    protected $table = 'submersible_data';

    protected $fillable = [
        'tegangan',
        'daya',
        'energi_harian',
        'energi_total',

        'debit',
        'volume_harian',
        'volume_total',
        'durasi_pemakaian_harian',
        'durasi_pemakaian_total',

        'intensitas_cahaya',
        'suhu',
        'suhu_harian',
        'durasi_koneksi'
    ];

    public static function getTodayData()
    {
        return self::select('energi_harian', 'volume_harian', 'suhu_harian', 'durasi_pemakaian_harian', 'durasi_koneksi', 'id', 'created_at')
            ->whereDate('created_at', Carbon::today())
            ->latest()->first();
    }

    public static function getLatestData()
    {
        return self::select('energi_total', 'volume_total', 'durasi_pemakaian_total', 'id', 'created_at')
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
