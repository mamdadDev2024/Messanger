<?php

namespace App\Observers;

use App\Models\Conversation;

class ConversationObserver
{
    /**
     * Handle the Conversation "created" event.
     */
    public function created(Conversation $conversation): void
    {
        //
    }

    /**
     * Handle the Conversation "updated" event.
     */
    public function updated(Conversation $conversation): void
    {
        //
    }

    /**
     * Handle the Conversation "deleted" event.
     */
    public function deleted(Conversation $conversation): void
    {
        //
    }

    /**
     * Handle the Conversation "restored" event.
     */
    public function restored(Conversation $conversation): void
    {
        //
    }

    /**
     * Handle the Conversation "force deleted" event.
     */
    public function forceDeleted(Conversation $conversation): void
    {
        //
    }
}
