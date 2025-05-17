<?php

namespace App\Jobs\Message;

use App\Models\File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ImageProccess implements ShouldQueue
{
    use Queueable;

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
        Log::debug('On Job => ' . $this->image->url);
    }
}
