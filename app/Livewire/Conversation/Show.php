<?php

namespace App\Livewire\Conversation;

use App\Events\Message\Sent;
use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Conversation $conversation;
    public $message = '';
    public $file;
    public $replyTo = null;
    public $showParticipants = false;

    protected $listeners = [
        'fileUploaded' => 'handleFileUploaded',
        'echo:conversation.{conversation.id},MessageSent' => '$refresh',
        'messageDeleted' => '$refresh'
    ];

    public function getListeners()
    {
        return [
            "echo-private:conversation.{$this->conversation->id},MessageSent" => '$refresh',
            'fileUploaded' => 'handleFileUploaded',
            'messageDeleted' => '$refresh'
        ];
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|max:10240'
        ]);

        $message = $this->conversation->messages()->create([
            'text' => $this->message,
            'user_id' => auth()->id(),
            'reply_to_id' => $this->replyTo
        ]);

        broadcast(new Sent($message))->toOthers();

        $this->reset(['message', 'replyTo']);
        $this->dispatch('messageSent');
    }

    public function setReplyTo($messageId)
    {
        $this->replyTo = $messageId;
    }

    public function cancelReply()
    {
        $this->replyTo = null;
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);
        
        if ($message && ($message->user_id === auth()->id() || $this->conversation->isAdmin(auth()->user()))) {
            $message->delete();
            $this->dispatch('messageDeleted');
        }
    }

    public function toggleParticipants()
    {
        $this->showParticipants = !$this->showParticipants;
    }

    public function render()
    {
        return view('livewire.conversation.show', [
            'messages' => $this->conversation->messages()
                ->with(['sender', 'file', 'replyTo.sender'])
                ->latest()
                ->paginate(50),
            'participants' => $this->conversation->participants
        ]);
    }
}
