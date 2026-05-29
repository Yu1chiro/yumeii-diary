<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Public Frontend
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/story/{slug}', [FrontendController::class, 'show'])->name('story.show');

// Route Upload ImageKit
Route::post('/upload-imagekit', function (Request $request, ImageKitService $imageKitService) {
    if (!$request->has('image_base64') || !$request->has('file_name')) {
        return response()->json(['success' => false, 'message' => 'Data gambar tidak valid'], 400);
    }

    try {
        $base64String = $request->image_base64;
        
        // HAPUS PREFIX "data:image/...;base64,"
        // Ini adalah kunci agar SDK ImageKit tidak mereturn error Status Code 0
        if (strpos($base64String, ';base64,') !== false) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
        }

        // Upload ke ImageKit (sudah base64 murni)
        $result = $imageKitService->uploadBase64(
            $base64String,
            $request->file_name
        );

        return response()->json([
            'success' => true, 
            'url' => $result->url 
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'ImageKit Error: ' . $e->getMessage()
        ], 500);
    }
})->withoutMiddleware([VerifyCsrfToken::class]);