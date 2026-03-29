<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class PublicStorageUrl
{
    /**
     * Resolve a stored path or absolute URL for display (images, links).
     */
    public static function url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        $disk = config('filesystems.uploads_disk');

        return Storage::disk($disk)->url($path);
    }
}
