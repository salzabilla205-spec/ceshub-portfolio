<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'role',
        'angkatan',
        'photo',
        'photo_position',
        'email',
        'linkedin_url',
        'is_active',
        'is_supervisor',
    ];
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_supervisor' => 'boolean',
        ];
    }
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }
}