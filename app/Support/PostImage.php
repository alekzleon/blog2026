<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostImage
{
    protected const DIRECTORY = 'posts';
    protected const LEGACY_PREFIXES = [
        'project/storage/app/public/',
        'storage/app/public/',
        'storage/',
    ];

    public static function storeUpload(UploadedFile $file, ?string $baseName = null): string
    {
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $path = self::buildRelativePath($extension, $baseName);
        $destination = public_path($path);

        File::ensureDirectoryExists(dirname($destination));
        $file->move(dirname($destination), basename($destination));

        return $path;
    }

    public static function storeBinary(string $contents, string $extension, ?string $baseName = null): string
    {
        $path = self::buildRelativePath($extension, $baseName);
        $destination = public_path($path);

        File::ensureDirectoryExists(dirname($destination));
        File::put($destination, $contents);

        return $path;
    }

    public static function delete(?string $path): void
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return;
        }

        $localPath = self::stripLegacyPrefixes($normalizedPath);
        $publicFile = public_path($localPath);

        if (File::exists($publicFile)) {
            File::delete($publicFile);
        }
    }

    public static function url(?string $path): ?string
    {
        $normalizedPath = self::normalizePath($path);

        if (! $normalizedPath) {
            return null;
        }

        if (Str::startsWith($normalizedPath, ['http://', 'https://', '//'])) {
            return $normalizedPath;
        }

        return asset(self::stripLegacyPrefixes($normalizedPath));
    }

    protected static function buildRelativePath(string $extension, ?string $baseName = null): string
    {
        $safeBaseName = Str::slug(Str::limit($baseName ?: 'post', 70, '')) ?: 'post';
        $safeExtension = ltrim(strtolower($extension), '.');
        $fileName = "{$safeBaseName}-" . now()->format('YmdHis') . '-' . Str::random(6) . ".{$safeExtension}";

        return self::DIRECTORY . '/' . $fileName;
    }

    protected static function normalizePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalizedPath = str_replace('\\', '/', trim($path));

        if (Str::startsWith($normalizedPath, ['http://', 'https://', '//'])) {
            return $normalizedPath;
        }

        return ltrim($normalizedPath, '/');
    }

    protected static function stripLegacyPrefixes(string $path): string
    {
        foreach (self::LEGACY_PREFIXES as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                return Str::after($path, $prefix);
            }
        }

        return $path;
    }
}
