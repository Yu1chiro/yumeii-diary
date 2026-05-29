<?php

namespace App\Services;

use Illuminate\Support\Str;
use ImageKit\ImageKit;
use Illuminate\Support\Facades\Log;

class ImageKitService
{
    /**
     * Upload base64 murni ke ImageKit tanpa kompresi GD
     */
    public static function uploadImage(string $base64Data, string $folder = '/scrapbook_memories'): ?string
    {
        try {
            // 1. Hapus tulisan "data:image/png;base64," di awal teks (Wajib untuk ImageKit)
            if (strpos($base64Data, ';base64,') !== false) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            }

            // 2. Hubungkan ke ImageKit menggunakan konfigurasi dari .env
            $imageKit = new ImageKit(
                env('IMAGEKIT_PUBLIC_KEY'),
                env('IMAGEKIT_PRIVATE_KEY'),
                env('IMAGEKIT_URL_ENDPOINT')
            );

            // 3. Langsung upload teks base64 ke ImageKit
            $upload = $imageKit->uploadFile([
                'file' => $base64Data,
                'fileName' => Str::random(10) . '.jpg',
                'folder' => $folder,
            ]);

            // 4. Jika ada error dari ImageKit, catat errornya dan kembalikan null
            if (isset($upload->error) && $upload->error) {
                Log::error('ImageKit API Error: ' . json_encode($upload->error));
                return null;
            }

            // 5. Kembalikan URL gambar yang berhasil diupload
            return $upload->result->url ?? null;

        } catch (\Exception $e) {
            // Catat jika ada error sistem
            Log::error('ImageKit Sistem Error: ' . $e->getMessage());
            return null;
        }
    }
}