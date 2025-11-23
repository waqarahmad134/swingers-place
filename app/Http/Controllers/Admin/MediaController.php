<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $type = $request->get('type', 'all');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $files = Storage::disk('public')->files('media');
        
        $media = collect($files)->map(function ($file) {
            $filename = basename($file);
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            $isImage = in_array($extension, $imageExtensions);
            
            // Generate correct URL using asset() helper
            $url = asset('storage/' . $file);
            
            return [
                'filename' => $filename,
                'original_name' => $filename,
                'url' => $url,
                'size' => Storage::disk('public')->size($file),
                'formatted_size' => $this->formatBytes(Storage::disk('public')->size($file)),
                'is_image' => $isImage,
                'alt_text' => '',
                'caption' => '',
                'description' => '',
                'created_at' => Storage::disk('public')->lastModified($file),
            ];
        });

        // Filter by search
        if ($search) {
            $media = $media->filter(function ($item) use ($search) {
                return stripos($item['original_name'], $search) !== false;
            });
        }

        // Filter by type
        if ($type !== 'all') {
            if ($type === 'image') {
                $media = $media->where('is_image', true);
            } else {
                $media = $media->where('is_image', false);
            }
        }

        // Sort
        $sortOrderValue = $sortOrder === 'asc' ? 'asc' : 'desc';
        if ($sortBy === 'size') {
            $media = $media->sortBy('size', SORT_REGULAR, $sortOrderValue === 'desc');
        } elseif ($sortBy === 'original_name') {
            $media = $media->sortBy('original_name', SORT_REGULAR, $sortOrderValue === 'desc');
        } else {
            $media = $media->sortBy('created_at', SORT_REGULAR, $sortOrderValue === 'desc');
        }

        // Paginate manually
        $perPage = 30;
        $currentPage = $request->get('page', 1);
        $items = $media->values();
        $total = $items->count();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedMedia = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.media.index', [
            'media' => $paginatedMedia,
        ]);
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'success' => true,
            'url' => $url,
            'path' => $path,
        ]);
    }

    public function getAll(): JsonResponse
    {
        $files = Storage::disk('public')->files('media');
        $media = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'url' => asset('storage/' . $file),
                'size' => Storage::disk('public')->size($file),
            ];
        });

        return response()->json($media);
    }

    public function show(string $filename): JsonResponse
    {
        $path = 'media/' . $filename;
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        $isImage = in_array($extension, $imageExtensions);

        return response()->json([
            'success' => true,
            'media' => [
                'filename' => $filename,
                'original_name' => $filename,
                'url' => asset('storage/' . $path),
                'size' => Storage::disk('public')->size($path),
                'formatted_size' => $this->formatBytes(Storage::disk('public')->size($path)),
                'is_image' => $isImage,
                'alt_text' => '',
                'caption' => '',
                'description' => '',
                'created_at' => Storage::disk('public')->lastModified($path),
            ],
        ]);
    }

    public function update(Request $request, string $filename)
    {
        // Media update functionality
        return response()->json(['success' => true]);
    }

    public function destroy(string $filename): JsonResponse
    {
        $path = 'media/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json(['success' => true]);
    }
}

