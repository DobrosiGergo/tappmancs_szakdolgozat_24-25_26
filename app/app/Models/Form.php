<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'form_messages';

    protected $fillable = [
        'subject',
        'message',
        'uuid',
    ];

    protected $hidden = ['user_id'];

    protected static function boot()
    {
        parent::boot();
        static::bootHasUuid();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
