<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSubmersibleLog extends Model
{
    //
    protected $table = "lab_submersible_logs";

    protected $fillable = [
        "energi_harian",
        "durasi_pemakaian_harian",
        "suhu_harian",
        "active",
        "date"
    ];
}
