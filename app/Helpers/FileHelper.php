<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Upload a file to storage and return the path
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $oldFile
     * @return string
     */
    public static function uploadFile(UploadedFile $file, string $directory = 'cars', ?string $oldFile = null): string
    {
        // Delete old file if exists
        if ($oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        // Generate unique filename with original extension
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store the file in the public disk
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * Delete a file from storage
     *
     * @param string|null $path
     * @return bool
     */
    public static function deleteFile(?string $path): bool
    {
        if ($path) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get the full URL for a file
     *
     * @param string|null $path
     * @return string|null
     */
    public static function getFileUrl(?string $path): ?string
    {
        if ($path) {
            return Storage::disk('public')->url($path);
        }
        return null;
    }
} 