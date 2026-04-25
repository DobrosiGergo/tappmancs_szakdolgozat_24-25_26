<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'images',
        'owner_id',
    ];

    protected $attributes = [
        'images' => '[]',
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

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
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
        return $this->images_safe[0] ?? null;
    }

    public function getInitialsAttribute(): string
    {
        $n = trim((string) $this->name);
        if ($n === '') {
            return '??';
        }
        $parts   = preg_split('/\s+/', $n);
        $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));

        return implode('', $letters);
    }

    public function getAvatarBgAttribute(): string
    {
        $s    = (string) $this->name;
        $hash = 0;
        for ($i = 0; $i < mb_strlen($s); $i++) {
            $hash = (31 * $hash + ord(mb_substr($s, $i, 1))) & 0xFFFFFF;
        }
        $r = 200 + ($hash & 0x1F);
        $g = 180 + (($hash >> 5) & 0x1F);
        $b = 170 + (($hash >> 10) & 0x1F);
        $r = min($r, 255);
        $g = min($g, 255);
        $b = min($b, 255);

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }
}
