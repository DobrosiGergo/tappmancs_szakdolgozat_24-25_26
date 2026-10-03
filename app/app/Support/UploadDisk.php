<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * Single source of truth for the disk that holds user-uploaded images
 * (pet and shelter photos, plus the Livewire temporary previews).
 *
 * Locally this is the "public" disk. On serverless hosting the filesystem is
 * read-only, so UPLOADS_DISK points at an S3-compatible bucket instead.
 */
final class UploadDisk
{
    public static function name(): string
    {
        return config('filesystems.uploads');
    }

    public static function get(): Filesystem
    {
        return Storage::disk(self::name());
    }

    public static function url(string $path): string
    {
        return self::get()->url($path);
    }
}
