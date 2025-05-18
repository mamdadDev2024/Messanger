<?php

namespace App\Livewire;

use App\Events\Message\Read;
use App\Events\Message\Sent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class Home extends Component
{
    use WithFileUploads;

    public string $message = '';
    public $attachment;
    public $conversations;
    public ?Conversation $activeConversation = null;
    public string $searchQuery = '';
    
    protected $listeners = [
        'messageReceived' => 'refreshMessages',
        'conversationCreated' => 'refreshConversations'
    ];

    protected function rules(): array
    {
        return [
            'message' => 'required_without:attachment|string|max:2000',
            'attachment' => [
                'nullable',
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,gif,doc,docx,pdf,txt'
            ]
        ];
    }

    public function mount(): void
    {
        $this->loadConversations();
    }

    public function loadConversations(): void
    {
        $this->conversations = Cache::remember(
            key: "user_". auth()->id() ."_conversations",
            ttl: now()->addMinutes(15),
            callback: fn() => auth()->user()->conversations()
                ->with([
                    'lastMessage:sender_id,conversation_id,content,created_at',
                    'participants:id,name,avatar'
                ])
                ->withUnreadMessages(auth()->id())
                ->latest('updated_at')
                ->get()
        );
    }

    // در کامپوننت Home
    public function markMessagesAsRead(): void
    {
        if (!$this->activeConversation) return;

        $unreadMessages = $this->activeConversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->get();

        if ($unreadMessages->isNotEmpty()) {
            Message::whereIn('id', $unreadMessages->pluck('id'))
                ->update([
                    'read_at' => now(),
                    'status' => 'read'
                ]);
            
            foreach ($unreadMessages as $message) {
                broadcast(new Read(
                    message_id: $message->id,
                    conversation_id: $this->activeConversation->id,
                    conversation_type: $this->activeConversation->type
                ));
            }
        }
    }

    public function searchUsers()
    {
        $this->validate(['searchQuery' => 'string|min:2|max:50']);

        return User::query()
            ->where('name', 'like', "%{$this->searchQuery}%")
            ->where('id', '!=', auth()->id())
            ->limit(5)
            ->get(['id', 'name', 'avatar', 'last_seen_at']);
    }

    public function sendMessage(): void
    {
        $this->validate();

        try {
            $message = $this->createMessage();
            $this->broadcastMessage($message);
            $this->updateConversation($message);
            $this->resetInputs();
        } catch (\Exception $e) {
            $this->dispatch('error', 'پیام ارسال نشد. لطفاً مجدداً تلاش کنید.');
        }
    }

    private function createMessage(): Message
    {
        return Message::create([
            'content' => $this->message,
            'sender_id' => auth()->id(),
            'conversation_id' => $this->activeConversation->id,
            'type' => $this->detectMessageType(),
            'file_id' => $this->storeAttachment()
        ]);
    }

    private function detectMessageType(): string
    {
        if ($this->attachment) {
            return match ($this->attachment->getMimeType()) {
                'image/jpeg', 'image/png', 'image/gif' => 'image',
                default => 'file'
            };
        }
        return 'text';
    }

    private function storeAttachment(): ?int
    {
        if (!$this->attachment) return null;

        $path = $this->attachment->store('attachments/' . auth()->id(), 'private');
        
        return auth()->user()->files()->create([
            'path' => $path,
            'mime_type' => $this->attachment->getMimeType(),
            'size' => $this->attachment->getSize()
        ])->id;
    }

    private function broadcastMessage(Message $message): void
    {
        broadcast(new Sent(
            message: $message->load(['sender', 'file']),
            conversation: $this->activeConversation
        ))->toOthers();
    }

    private function updateConversation(Message $message): void
    {
        $this->activeConversation->update([
            'last_message_id' => $message->id,
            'updated_at' => now()
        ]);

        $this->conversations->fresh();
    }

    private function resetInputs(): void
    {
        $this->reset(['message', 'attachment']);
        $this->dispatch('messageSent');
    }

    public function refreshMessages(): void
    {
        $this->activeConversation?->refresh();
    }

    public function refreshConversations(): void
    {
        Cache::forget("user_{auth()->id()}_conversations");
        $this->loadConversations();
    }

    public function render()
    {
        return view('livewire.home', [
            'searchResults' => $this->searchQuery ? $this->searchUsers() : collect()
        ]);
    }
}