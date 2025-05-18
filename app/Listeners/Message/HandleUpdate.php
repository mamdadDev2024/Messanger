<?php

namespace App\Listeners\Message;

use App\Events\Message\Update;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Message\MessageUpdateRequest;
use App\Events\Message\MessageUpdated;
use App\Actions\Message as ActionsMessage;

class HandleUpdate
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
    public function handle(MessageUpdateRequest $event): void
    {
        $message = ActionsMessage::update($event);
        event(new MessageUpdated($message));
    }
}
