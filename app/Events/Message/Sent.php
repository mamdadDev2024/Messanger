<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSentRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $text,
        public string $conversation_type,
        public string $conversation_id,
        public string $sender_id,
        public array|null $file = null,
        public $reply_to_id = null,
        public $forwarded_from_id = null
    ) {}
}

// رویداد Broadcast برای ارسال پیام به همه کاربران گفتگو
class MessageSent implements ShouldBroadcast
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
