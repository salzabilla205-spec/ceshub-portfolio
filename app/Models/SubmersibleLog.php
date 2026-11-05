<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmersibleLog extends Model
{
    //
    protected $fillable = [
        "energi_harian",
        "volume_harian",
        "durasi_pemakaian_harian",
        "suhu_harian",
        "active",
        "date"
    ];
}
