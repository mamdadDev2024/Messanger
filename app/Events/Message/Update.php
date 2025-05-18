<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUpdateRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $message_id,
        public string $conversation_type,
        public string $conversation_id,
        public string $text,
        public $file = null
    ) {}
}

class MessageUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("message.{$this->message['conversation_type']}.{$this->message['conversation_id']}")
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message
        ];
    }
}
