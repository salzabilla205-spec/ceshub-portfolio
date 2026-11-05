<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    public const STATUSES = [
        'open' => 'Perlu tindak lanjut',
        'done' => 'Selesai',
        'info' => 'Catatan saja',
    ];

    protected $fillable = ['note_date', 'progress', 'problems', 'next_steps', 'status'];

    protected function casts(): array
    {
        return ['note_date' => 'date'];
    }

    public function setNoteDateAttribute($value): void
    {
        $this->attributes['note_date'] = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
    }
}
