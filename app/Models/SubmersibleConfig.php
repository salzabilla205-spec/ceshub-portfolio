<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmersibleConfig extends Model
{
    protected $table = "submersible_config";

    protected $fillable = [
        "active_button",
        "lampu1",
        "lampu2",
    ];
}