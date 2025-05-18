<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleteRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $message_id,
        public string $conversation_type,
        public string $conversation_id
    ) {}
}

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $message_id,
        public $conversation_type,
        public $conversation_id
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("message.{$this->conversation_type}.{$this->conversation_id}")
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->message_id
        ];
    }
}
