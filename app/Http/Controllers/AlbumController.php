<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\AlbumImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Create a new album.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:3', 'max:255'],
        ]);

        $user = Auth::user();
        
        $album = Album::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'password' => $request->password, // Will be hashed by model mutator
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Album created successfully!',
            'album' => $album,
        ]);
    }

    /**
     * Show album detail page.
     */
    public function show($id)
    {
        $album = Album::with(['images', 'user'])->findOrFail($id);
        $user = Auth::user();
        $isOwnAlbum = $album->user_id === $user->id;
        
        // Check if album is private and requires password
        if ($album->is_private && !$isOwnAlbum) {
            // Check if password was provided in session
            if (!session()->has('album_access_' . $album->id)) {
                return view('pages.albums.password', compact('album'));
            }
        }

        return view('pages.albums.show', [
            'album' => $album,
            'user' => $album->user,
            'isOwnAlbum' => $isOwnAlbum,
        ]);
    }

    /**
     * Verify album password and grant access.
     */
    public function verifyPassword(Request $request, $id)
    {
        $album = Album::findOrFail($id);
        
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if ($album->checkPassword($request->password)) {
            session(['album_access_' . $album->id => true]);
            return redirect()->route('albums.show', $album->id);
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    /**
     * Upload images to album.
     */
    public function uploadImages(Request $request, $id)
    {
        $album = Album::findOrFail($id);
        $user = Auth::user();

        // Check if user owns the album
        if ($album->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to upload to this album.',
            ], 403);
        }

        $request->validate([
            'images.*' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        $uploadedImages = [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('profiles/albums/' . $album->id, 'public');
                
                $albumImage = AlbumImage::create([
                    'album_id' => $album->id,
                    'image_path' => $path,
                ]);
                
                $uploadedImages[] = $albumImage;
            }
            
            // Update image count
            $album->increment('image_count', count($uploadedImages));
        }

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully!',
            'images' => $uploadedImages,
        ]);
    }

    /**
     * Delete an album.
     */
    public function destroy($id)
    {
        $album = Album::with('images')->findOrFail($id);
        $user = Auth::user();

        // Check if user owns the album
        if ($album->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this album.',
            ], 403);
        }

        // Delete all images from storage
        foreach ($album->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Delete album (cascade will delete images from database)
        $album->delete();

        return response()->json([
            'success' => true,
            'message' => 'Album deleted successfully!',
        ]);
    }

    /**
     * Delete an image from album.
     */
    public function deleteImage($albumId, $imageId)
    {
        $album = Album::findOrFail($albumId);
        $image = AlbumImage::findOrFail($imageId);
        $user = Auth::user();

        // Check if user owns the album
        if ($album->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this image.',
            ], 403);
        }

        // Delete image from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete image from database
        $image->delete();

        // Update image count
        $album->decrement('image_count');

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!',
        ]);
    }
}
