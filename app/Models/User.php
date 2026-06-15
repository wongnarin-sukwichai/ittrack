<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $rememberTokenName = null;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'role',
    ];

    protected $hidden = [];

    protected function casts(): array
    {
        return [];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
