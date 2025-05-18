<?php

namespace App\Livewire\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Conversation $conversation;

    public function render()
    {
        return view('livewire.conversation.show', [
            'messages' => $this->conversation->messages()
                ->with(['sender', 'file', 'replyTo.sender']),
            'participants' => $this->conversation->participants
        ]);
    }
}
