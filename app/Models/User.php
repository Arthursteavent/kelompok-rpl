<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PENULIS = 'penulis';
    public const ROLE_PENGLIHAT = 'penglihat';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPenulis(): bool
    {
        return $this->role === self::ROLE_PENULIS;
    }

    public function isPenglihat(): bool
    {
        return $this->role === self::ROLE_PENGLIHAT;
    }

    public function canManageArticles(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PENULIS], true);
    }

    public function canManageCategories(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_PENULIS => 'Penulis',
            default => 'User Penglihat',
        };
    }

    public function roleBadgeColor(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'danger',
            self::ROLE_PENULIS => 'primary',
            default => 'success',
        };
    }
}
