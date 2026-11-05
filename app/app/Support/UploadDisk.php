<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

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

    public static function moveTempImages(string $context, string $folder): array
    {
        $paths = [];

        foreach (session()->pull($context . '_temp_images', []) as $tmpPath) {
            if (! self::get()->exists($tmpPath)) {
                continue;
            }

            $newPath = $folder . '/' . basename($tmpPath);
            self::get()->move($tmpPath, $newPath);
            $paths[] = $newPath;
        }

        return $paths;
    }
}
