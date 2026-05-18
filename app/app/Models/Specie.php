<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'species';

    protected $fillable = [
        'uuid',
        'name',
    ];

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function selectOptions(): array
    {
        $options = [];
        foreach (static::orderBy('name')->get(['id', 'name']) as $specie) {
            $options[] = ['value' => (string) $specie->id, 'label' => $specie->name];
        }
        return $options;
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
