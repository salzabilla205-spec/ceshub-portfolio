<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'thumbnail',
        'images',
        'project_id',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'images' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function getMediaImagesAttribute(): array
    {
        return collect([$this->thumbnail, ...($this->images ?? [])])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->unique()->values()->all();
    }
}
