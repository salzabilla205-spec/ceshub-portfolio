<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Jamur extends Model
{
    protected $connection = 'jamur';
    
    // Nama tabel di database (dibuat oleh migration jamur_data)
    protected $table = 'jamur_data';

    // Kolom yang boleh diisi lewat create()
    protected $fillable = [
        'suhu_node1',
        'suhu_node2',
        'kelembaban_node1',
        'kelembaban_node2',
        'status_relay',
        'mode',
    ];

    // Ambil 1 baris data terbaru
    public static function getLatestData()
    {
        return self::latest()->first();
    }

    // Ambil data terbaru khusus hari ini
    public static function getTodayData()
    {
        return self::whereDate('created_at', Carbon::today())
            ->latest()->first();
    }

    // Hitung jumlah data hari ini (untuk rata-rata, dsb.)
    public static function todayDataCount()
    {
        return self::whereDate('created_at', Carbon::today())->count();
    }
}
