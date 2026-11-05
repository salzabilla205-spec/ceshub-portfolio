<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    protected $table = 'environment';

    protected $fillable = [
        'intensitas_cahaya',
        'suhu',
        'suhu_harian',
    ];

    public static function todayDataCount()
    {
        return self::whereDate('created_at', Carbon::today())
            ->count();
    }

    public static function getTodayData()
    {
        return self::select('suhu_harian', 'id', 'created_at')
            ->whereDate('created_at', Carbon::today())
            ->latest()->first();
    }
}
