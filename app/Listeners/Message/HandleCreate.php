<?php

namespace App\Listeners\Message;

use App\Actions\Message as ActionsMessage;
use App\Events\Message\Sent;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Events\Message\MessageSentRequest;
use App\Events\Message\MessageSent;

class HandleCreate implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSentRequest $event): void
    {
        // ذخیره پیام و فایل (در اکشن)
        $message = ActionsMessage::store($event);
        // دیسپچ رویداد Broadcast برای همه کاربران گفتگو
        event(new MessageSent($message));
    }
}
