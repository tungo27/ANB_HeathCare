<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('services.cloudinary.cloud_name'),
                    'api_key'    => config('services.cloudinary.api_key'),
                    'api_secret' => config('services.cloudinary.api_secret'),
                ],
                'url' => ['secure' => true]
            ])
        );
    }

    public function uploadImage(string $filePath, string $folder = 'images'): array
    {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'resource_type' => 'image',
            'folder'        => $folder,
        ]);
        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    public function uploadVideo(string $filePath, string $folder = 'videos'): array
    {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'resource_type' => 'video',
            'folder'        => $folder,
        ]);
        return [
            'url'       => $result['secure_url'],
            'public_id' => $result['public_id'],
            'duration'  => $result['duration'] ?? null,
        ];
    }

    public function deleteImage(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => 'image']);
    }

    public function deleteVideo(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => 'video']);
    }

    public function listImages(string $folder = 'test'): array
    {
        $result = $this->cloudinary->adminApi()->assets([
            'type'        => 'upload',
            'prefix'      => $folder . '/',
            'resource_type' => 'image',
            'max_results' => 50,
        ]);

        return collect($result['resources'])->map(fn($img) => [
            'url'       => $img['secure_url'],
            'public_id' => $img['public_id'],
            'width'     => $img['width'],
            'height'    => $img['height'],
        ])->toArray();
    }
}
