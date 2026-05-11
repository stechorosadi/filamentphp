<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageConverter
{
    public static function toWebp(
        TemporaryUploadedFile $file,
        string $directory,
        string $disk = 'public',
        int $quality = 80
    ): string {
        if (! function_exists('imagewebp')) {
            return $file->store($directory, $disk);
        }

        $result = self::encodeToWebp($file->getRealPath(), $directory, $disk, $quality);

        return $result ?? $file->store($directory, $disk);
    }

    /**
     * Convert an image file at the given path to WebP and store it.
     * Returns the stored path, or null if the source cannot be decoded.
     */
    public static function encodeToWebp(
        string $sourcePath,
        string $directory,
        string $disk = 'public',
        int $quality = 80
    ): ?string {
        $raw = is_file($sourcePath) ? file_get_contents($sourcePath) : false;

        if ($raw === false) {
            return null;
        }

        $src = imagecreatefromstring($raw);

        if (! $src) {
            return null;
        }

        imagealphablending($src, false);
        imagesavealpha($src, true);

        ob_start();
        imagewebp($src, null, $quality);
        $webpData = ob_get_clean();
        imagedestroy($src);

        $path = $directory.'/'.Str::uuid().'.webp';
        Storage::disk($disk)->put($path, $webpData);

        return $path;
    }
}
