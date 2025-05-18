<?php

namespace App\Listeners\Message;

use App\Events\Message\MessageReadRequest;
use App\Events\Message\MessageRead;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleRead implements ShouldQueue
{
    public function __construct() {}

    public function handle(MessageReadRequest $event): void
    {
        $message = Message::findOrFail($event->message_id);
        // ثبت وضعیت خوانده‌شدن برای کاربر
        $message->readers()->syncWithoutDetaching([
            $event->user_id => ['read_at' => now()]
        ]);
        // دیسپچ رویداد Broadcast برای همه کاربران گفتگو
        event(new MessageRead($event->message_id, $event->user_id, $event->conversation_type, $event->conversation_id));
    }
} 