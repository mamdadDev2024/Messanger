<?php

namespace App\Listeners\Message;

use App\Events\Message\FileClassified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleFileClassified implements ShouldQueue
{
    public function __construct() {}

    public function handle(FileClassified $event): void
    {
        // این Listener فقط رویداد را Broadcast می‌کند (در خود FileClassified انجام می‌شود)
        // اگر نیاز به منطق اضافه بود اینجا اضافه می‌شود
    }
} 