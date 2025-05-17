<?php

namespace App\Listeners\Message;

use App\Actions\Message as ActionsMessage;
use App\Events\Message\Sent;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
    public function handle(Sent $event): void
    {
        Log::debug("On Lintener => " . $event->text ?? $event->conversation_id);
        ActionsMessage::store($event);
    }
}
