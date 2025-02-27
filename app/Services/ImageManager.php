<?php

namespace App\Services;

class ImageManager
{
    /**
     * @param array<string, mixed> $image
     */
    public static function upload(array $image, string $destination, string $prefix = ''): ?string
    {
        if (empty($image['tmp_name'])) {
            return null;
        }
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }
        $fileNameParts = explode('.', $image['name']);
        $extension = end($fileNameParts);
        $fileName = $prefix . uniqid('img_') . '.' . $extension;

        $absolutePath = rtrim($destination, '/') . '/' . $fileName;
        if (move_uploaded_file($image['tmp_name'], $absolutePath)) {
            return $fileName;
        }
        return null;
    }

    public static function remove(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
