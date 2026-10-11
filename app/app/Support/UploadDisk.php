<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * The disk that holds every user-uploaded image: pet and shelter photos and
 * the temporary files of an upload in progress. Which disk that is comes from
 * UPLOADS_DISK (config/filesystems.php); it is the "public" disk both locally
 * and on the server.
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

    /**
     * Moves the images the ImageUploader stored for the given context ("pet"
     * or "shelter") from the temp directory into $folder.
     *
     * @return string[] the new paths of the moved images
     */
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
