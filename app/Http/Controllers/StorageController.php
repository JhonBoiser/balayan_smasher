<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    // Display a stored image (e.g. /storage/products/image.jpg)
    public function show($folder, $filename)
    {
        $path = "public/{$folder}/{$filename}";

        if (!Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        $file = Storage::get($path);
        $mime = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mime);
    }

    // Upload a new file
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
            'folder' => 'nullable|string'
        ]);

        $folder = $request->folder ?? 'products';
        $filename = Str::random(10) . '.' . $request->file->extension();

        $path = $request->file('file')->storeAs("public/{$folder}", $filename);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'path' => str_replace('public/', '', $path)
        ]);
    }

    // Delete a file
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $fullPath = 'public/' . $request->path;

        if (Storage::exists($fullPath)) {
            Storage::delete($fullPath);
            return response()->json(['message' => 'File deleted successfully.']);
        }

        return response()->json(['error' => 'File not found.'], 404);
    }
}
