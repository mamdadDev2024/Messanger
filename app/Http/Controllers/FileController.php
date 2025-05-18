<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'conversation_id' => 'required|exists:conversations,id'
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        
        // Generate a unique filename
        $filename = Str::uuid() . '.' . $extension;
        
        // Store the file
        $path = $file->storeAs('uploads/' . date('Y/m'), $filename, 'public');
        
        // Create file record
        $fileModel = File::create([
            'url' => $path,
            'type' => $extension,
            'size' => $file->getSize(),
            'mime_type' => $mimeType,
            'original_name' => $originalName,
            'class' => 'normal' // Default class, will be updated by observer if it's an image
        ]);

        return response()->json([
            'success' => true,
            'file' => $fileModel
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        if (Storage::exists($file->url)) {
            Storage::delete($file->url);
        }
        
        $file->delete();
        
        return response()->json(['success' => true]);
    }
}
