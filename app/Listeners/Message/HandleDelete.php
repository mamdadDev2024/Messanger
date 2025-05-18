<?php

namespace App\Listeners\Message;

use App\Events\Message\Delete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Message\MessageDeleteRequest;
use App\Events\Message\MessageDeleted;
use App\Actions\Message as ActionsMessage;

class HandleDelete
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
    public function handle(MessageDeleteRequest $event): void
    {
        ActionsMessage::delete($event);
        event(new MessageDeleted($event->message_id, $event->conversation_type, $event->conversation_id));
    }
}
