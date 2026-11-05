<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class DashboardCredential extends Model implements Authenticatable
{
    use Authorizable;

    protected $fillable = [
        'project_id',
        'username',
        'password',
        'password_hash',
        'password_encrypted',
    ];

    protected $hidden = [
        'password_hash',
        'password_encrypted',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function setPasswordAttribute(string $plainPassword): void
    {
        $this->attributes['password_hash'] = Hash::make($plainPassword);
        $this->attributes['password_encrypted'] = Crypt::encryptString($plainPassword);
    }

    public function getPlainPasswordAttribute(): string
    {
        return Crypt::decryptString($this->password_encrypted);
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->id;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }
}