<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostImage
{
    protected const DIRECTORY = 'posts';

    public static function storeUpload(UploadedFile $file, ?string $baseName = null): string
    {
        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $path = self::buildRelativePath($extension, $baseName);

        $destination = self::baseFilesystemPath($path);

        File::ensureDirectoryExists(dirname($destination));
        $file->move(dirname($destination), basename($destination));

        return $path;
    }

    public static function storeBinary(string $contents, string $extension, ?string $baseName = null): string
    {
        $path = self::buildRelativePath($extension, $baseName);

        $destination = self::baseFilesystemPath($path);

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

        $localPath = self::stripStoragePrefix($normalizedPath);

        $configuredFile = self::baseFilesystemPath($localPath);
        if (File::exists($configuredFile)) {
            File::delete($configuredFile);
        }

        $publicFile = public_path($localPath);
        if ($publicFile !== $configuredFile && File::exists($publicFile)) {
            File::delete($publicFile);
        }

        $legacyStoragePath = storage_path('app/public/' . $localPath);
        if (File::exists($legacyStoragePath)) {
            File::delete($legacyStoragePath);
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

        $localPath = self::stripStoragePrefix($normalizedPath);

        $baseUrl = rtrim((string) config('app.post_image_url', ''), '/');

        if ($baseUrl !== '') {
            return $baseUrl . '/' . ltrim($localPath, '/');
        }

        self::ensurePublicCopy($localPath);

        return asset($localPath);
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

    protected static function stripStoragePrefix(string $path): string
    {
        return Str::startsWith($path, 'storage/')
            ? Str::after($path, 'storage/')
            : $path;
    }

    protected static function ensurePublicCopy(string $path): void
    {
        $publicFile = public_path($path);
        $legacyStorageFile = storage_path('app/public/' . $path);

        if (File::exists($publicFile) || ! File::exists($legacyStorageFile)) {
            return;
        }

        File::ensureDirectoryExists(dirname($publicFile));
        File::copy($legacyStorageFile, $publicFile);
    }

    protected static function baseFilesystemPath(string $path): string
    {
        return config('app.post_image_root') === 'storage'
            ? storage_path('app/public/' . $path)
            : public_path($path);
    }
}
