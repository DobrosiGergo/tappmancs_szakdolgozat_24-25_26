<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'pets';

    protected $appends = ['images_safe', 'first_image_url'];

    protected $fillable = [
        'uuid',
        'name',
        'birth_date',
        'breed_id',
        'gender',
        'status',
        'description',
        'images',
        'species_id',
        'shelter_id',
        'employee_id',
        'arrival_date',
    ];

    public const GENDERS = [
        'unknown' => 'Ismeretlen',
        'male'    => 'Hím',
        'female'  => 'Nőstény',
    ];

    public const STATUSES = [
        'free'     => 'Elérhető',
        'reserved' => 'Foglalva',
        'adopted'  => 'Örökbefogadott',
    ];

    protected function casts()
    {
        return [
            'arrival_date' => 'date',
            'birth_date'   => 'date',
            'images'       => 'array',
        ];
    }

    protected $hidden = ['employee_id'];

    protected $attributes = [
        'status' => 'free',
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

    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }

    public function species()
    {
        return $this->belongsTo(Specie::class, 'species_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function adoption()
    {
        return $this->hasOne(Adoption::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function formMessages()
    {
        return $this->hasMany(Form::class, 'pet_id');
    }

    public function getImagesSafeAttribute()
    {
        if (is_array($this->images)) {
            return $this->images;
        }
        if (is_string($this->images)) {
            $tmp = json_decode($this->images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                return $tmp;
            }
        }

        return [];
    }

    public function getFirstImageUrlAttribute()
    {
        $arr = $this->images_safe;

        return count($arr) ? asset('storage/' . $arr[0]) : null;
    }

    public static function genderOptions(): array
    {
        return collect(self::GENDERS)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }

    public static function statusOptions(): array
    {
        return collect(self::STATUSES)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }

    public function getGenderLabelAttribute(): string
    {
        return self::GENDERS[$this->gender] ?? 'Ismeretlen';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'adopted'  => 'Örökbefogadott',
            'reserved' => 'Foglalva',
            'free'     => 'Elérhető',
            default    => 'Ismeretlen',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'adopted'  => 'bg-neutral-100 text-neutral-500 ring-neutral-200',
            'reserved' => 'bg-amber-50 text-amber-600 ring-amber-200',
            'free'     => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            default    => 'bg-neutral-100 text-neutral-500 ring-neutral-200',
        };
    }

    public function getAgeAttribute(): ?float
    {
        if (! $this->birth_date) {
            return null;
        }

        return round($this->birth_date->diffInDays(now()) / 365, 1);
    }
}
