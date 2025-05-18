<?php

namespace App\Livewire\Conversation\Group;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $searchQuery = '';
    public $selectedUsers = [];

    public function addUser($userId)
    {
        if (!in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers[] = $userId;
        }
    }

    public function removeUser($userId)
    {
        $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != $userId);
    }

    public function createGroup()
    {
        $this->validate([
            'name' => 'required|min:3|max:50',
            'description' => 'nullable|max:255',
            'selectedUsers' => 'required|array|min:2'
        ]);

        $conversation = Conversation::create([
            'name' => $this->name,
            'description' => $this->description,
            'type' => 'group',
            'token' => Str::random(32)
        ]);

        // Add participants including the creator
        $conversation->participants()->attach(array_merge(
            $this->selectedUsers,
            [auth()->id()]
        ), ['is_admin' => false]);

        // Make creator admin
        $conversation->participants()->updateExistingPivot(auth()->id(), ['is_admin' => true]);

    }

    public function render()
    {
        $searchResults = [];
        if (strlen($this->searchQuery) >= 2) {
            $searchResults = User::where('name', 'like', "%{$this->searchQuery}%")
                ->where('id', '!=', auth()->id())
                ->whereNotIn('id', $this->selectedUsers)
                ->take(5)
                ->get();
        }

        $selectedUsers = User::whereIn('id', $this->selectedUsers)->get();

        return view('livewire.conversation.group.create', [
            'searchResults' => $searchResults,
            'selectedUsers' => $selectedUsers
        ]);
    }
}
