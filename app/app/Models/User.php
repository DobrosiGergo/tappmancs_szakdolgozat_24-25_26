<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasUuid, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phoneNumber',
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::bootHasUuid();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function shelter(): HasOne
    {
        return $this->hasOne(Shelter::class, 'owner_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'employee_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function adoptions()
    {
        return $this->hasMany(Adoption::class);
    }

    public function formMessages()
    {
        return $this->hasMany(Form::class);
    }
}
