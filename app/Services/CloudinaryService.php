<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    public function upload(UploadedFile $file, string $folder = 'dora'): ?string
    {
        try {
            $result = cloudinary()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ],
            ]);
            return $result->getSecurePath();
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    public function delete(string $publicId): bool
    {
        try {
            cloudinary()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
