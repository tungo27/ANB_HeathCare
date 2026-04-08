<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class CloudinaryTestController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary) {}

    // Trang test
    public function index()
    {
        return view('cloudinary-test');
    }

    // Upload ảnh
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimetypes:image/jpeg,image/png,image/webp|max:5120',
        ]);

        try {
            $result = $this->cloudinary->uploadImage($request->file('image')->getRealPath());
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Upload video
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/quicktime|max:512000',
        ]);

        try {
            $result = $this->cloudinary->uploadVideo(
                $request->file('video')->getRealPath(),
                'test'
            );
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getImages()
    {
        try {
            $result = (new \App\Services\CloudinaryService)->listImages('test');
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
