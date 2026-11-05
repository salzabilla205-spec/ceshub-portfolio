<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HessTelemetry extends Model
{
    use HasFactory;

    protected $fillable = [
        'speed', 'rpm',
        'v_bat', 'i_bat', 'p_bat', 'soc_bat', 'wh_bat',
        'v_kap', 'i_kap', 'p_kap', 'soc_kap', 'wh_kap',
        'v_batkap', 'i_batkap', 'wh_batkap',
        'buck', 'boost'
    ];
}