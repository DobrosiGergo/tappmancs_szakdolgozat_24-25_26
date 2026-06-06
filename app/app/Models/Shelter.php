<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Form;

class Shelter extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'shelter';

    protected $casts = [
        'images'     => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'location',
        'images',
        'owner_id',
    ];

    protected $attributes = [
        'images' => '[]',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function workers(): HasMany
    {
        return $this->hasMany(User::class, 'shelter_id');
    }

    public function formMessages(): HasMany
    {
        return $this->hasMany(Form::class, 'shelter_id');
    }

    public function getImagesSafeAttribute(): array
    {
        $raw = $this->getRawOriginal('images');

        if (is_array($this->images)) {
            return $this->images;
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    public function getCoverImageAttribute(): ?string
    {
        return collect($this->images_safe)->first();
    }

    public function getOwnerNameAttribute(): string
    {
        return $this->owner->name;
    }

    public function getExcerptAttribute(): string
    {
        return \Illuminate\Support\Str::limit($this->description, 120);
    }
}
