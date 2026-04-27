<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'comments';

    protected $hidden = [
        'user_id',
    ];

    protected $fillable = [
        'uuid',
        'content',
    ];

    protected static function boot()
    {
        parent::boot();
        static::bootHasUuid();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
