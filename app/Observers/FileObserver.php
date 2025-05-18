<?php

namespace App\Observers;

use App\Jobs\Message\ImageProccess;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileObserver
{
    /**
     * Handle the File "created" event.
     */
    public function created(File $file): void
    {
        if (str_starts_with($file->mime_type, 'image/')) {
            ImageProccess::dispatch($file)->onQueue('predict');
        }
    }

    /**
     * Handle the File "updated" event.
     */
    public function updated(File $file): void
    {
        if ($file->wasChanged('class')) {
            event(new \App\Events\Message\FileClassified($file));
        }
    }

    /**
     * Handle the File "deleted" event.
     */
    public function deleted(File $file): void
    {
        $path = public_path($file->url);

        if (file_exists($path)) {
            try {
                unlink($path);
            } catch (\Throwable $e) {
                Log::error("Failed to delete file from public path: {$path}. Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the File "restored" event.
     */
    public function restored(File $file): void
    {
        if ($file->isImage()) {
            ImageProccess::dispatch($file);
        }
    }

    /**
     * Handle the File "force deleted" event.
     */
    public function forceDeleted(File $file): void
    {
        $disk = Storage::disk('public');

        if ($disk->exists($file->url)) {
            try {
                $disk->delete($file->url);
            } catch (\Throwable $e) {
                Log::error("Failed to delete file from storage: {$file->url}. Error: " . $e->getMessage());
            }
        }
    }
}
