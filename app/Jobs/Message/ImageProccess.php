<?php

namespace App\Jobs\Message;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageProccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    protected $queue = 'predict';

    /**
     * Create a new job instance.
     */
    public function __construct(public File $image){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (!Storage::exists($this->image->url)) {
                Log::error('Image file not found: ' . $this->image->url);
                return;
            }

            $response = Http::timeout($this->timeout)
                ->attach(
                    'image',
                    Storage::get($this->image->url),
                    basename($this->image->url)
                )
                ->post(config('services.image_classifier.url'));

            if ($response->successful()) {
                $this->image->update([
                    'class' => $response->json('class'),
                    'metadata' => $response->json('metadata', [])
                ]);
                Log::info('Image classified successfully: ' . $this->image->url);
            } else {
                Log::error('Image classification failed with status: ' . $response->status());
                if ($this->attempts() >= $this->tries) {
                    $this->image->update(['class' => 'unclassified']);
                } else {
                    $this->release(30); // retry after 30 seconds
                }
            }
        } catch (\Exception $e) {
            Log::error('Image classification error: ' . $e->getMessage());
            if ($this->attempts() >= $this->tries) {
                $this->image->update(['class' => 'error']);
            } else {
                $this->release(30);
            }
        }
    }
}
