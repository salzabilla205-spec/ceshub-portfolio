<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'summary',
        'description',
        'thumbnail',
        'images',
        'specs',
        'category',
        'dashboard_url',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'published_at' => 'datetime',
            'images' => 'array',
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeMatchingResearch(\Illuminate\Database\Eloquent\Builder $query, string $term): void
    {
        $pattern = '%'.$term.'%';
        $query->where(function ($matches) use ($pattern) {
            $matches->where('name', 'like', $pattern)
                ->orWhere('summary', 'like', $pattern)
                ->orWhere('category', 'like', $pattern)
                ->orWhereHas('teamMembers', fn ($members) => $members->where('name', 'like', $pattern))
                ->orWhereExists(function ($supervisors) use ($pattern) {
                    $supervisors->selectRaw('1')->from('team_members')
                        ->where('is_supervisor', true)->where('name', 'like', $pattern);
                });
        });
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'project_team')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function getMediaImagesAttribute(): array
    {
        return collect([$this->thumbnail, ...($this->images ?? [])])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->unique()->values()->all();
    }
    public function getMonitoringPathAttribute(): ?string
    {
        $known = [
            'living-lab' => '/dashboard/smart-water-pump',
            'smart-water-pump' => '/dashboard/smart-water-pump',
            'jamur_riset' => '/dashboard/jamur',
            'jamur' => '/dashboard/jamur',
            'lab-scale' => '/dashboard/lab-scale',
            'digital-twin' => '/dashboard/digital-twin',
            'digital-twin-baterai' => '/dashboard/digital-twin',
            'hess' => '/dashboard/hess',
        ];
        if (isset($known[$this->slug])) return $known[$this->slug];
        $path = parse_url($this->dashboard_url ?? '', PHP_URL_PATH);
        if ($path === '/dashboard') return '/dashboard/smart-water-pump';
        if (is_string($path) && str_starts_with($path, '/dashboard/')) return $path;
        return null;
    }
}
