<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageReadRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $message_id,
        public string $conversation_type,
        public string $conversation_id,
        public string $user_id
    ) {}
}

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $message_id,
        public $user_id,
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
            'message_id' => $this->message_id,
            'user_id' => $this->user_id
        ];
    }
}
