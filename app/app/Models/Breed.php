<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'breeds';

    protected $fillable = [
        'uuid',
        'name',
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
