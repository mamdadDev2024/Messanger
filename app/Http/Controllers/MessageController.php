<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($conversationId)
    {
        $conversation = \App\Models\Conversation::findOrFail($conversationId);
        $this->authorize('view', $conversation);
        $messages = $conversation->messages()->with('sender', 'file')->latest()->take(50)->get()->reverse()->values();
        return response()->json($messages);
    }
}
