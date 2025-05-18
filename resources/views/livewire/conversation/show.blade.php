<div class="flex flex-col h-full">
    <!-- Header -->
    <div class="bg-base-100 border-b border-base-300 p-4 flex items-center justify-between">
        <div class="flex items-center">
            <div class="avatar placeholder">
                <div class="bg-neutral text-neutral-content rounded-full w-10">
                    @if($conversation->type === 'group')
                        <i class="fas fa-users"></i>
                    @elseif($conversation->type === 'channel')
                        <i class="fas fa-bullhorn"></i>
                    @else
                        <span>{{ substr($conversation->participants->first()->name, 0, 1) }}</span>
                    @endif
                </div>
            </div>
            <div class="ml-3">
                <h2 class="font-medium">
                    @if($conversation->type === 'private')
                        {{ $conversation->participants->first()->name }}
                    @else
                        {{ $conversation->name }}
                    @endif
                </h2>
                <p class="text-sm opacity-70">
                    @if($conversation->type === 'group')
                        {{ $conversation->participants->count() }} members
                    @elseif($conversation->type === 'channel')
                        {{ $conversation->subscribers->count() }} subscribers
                    @else
                        {{ $conversation->participants->first()->status }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($conversation->type !== 'private')
                <button wire:click="toggleParticipants" class="btn btn-circle btn-ghost">
                    <i class="fas fa-users"></i>
                </button>
            @endif
            <div class="dropdown dropdown-end">
                <button class="btn btn-circle btn-ghost">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul tabindex="0" class="dropdown-content menu menu-sm z-[1] p-2 shadow bg-base-200 rounded-box w-52">
                    <li><a wire:click="leaveConversation" class="text-error">Leave</a></li>
                    @if($conversation->isAdmin(auth()->user()))
                        <li><a wire:click="deleteConversation" class="text-error">Delete</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-200" id="messages-container">
        @foreach($messages->reverse() as $message)
            <div wire:key="msg-{{ $message->id }}" 
                 class="chat {{ $message->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}">
                <div class="chat-header mb-1">
                    {{ $message->sender->name }}
                    <time class="text-xs opacity-50 ml-1">{{ $message->created_at->format('H:i') }}</time>
                </div>
                
                @if($message->replyTo)
                    <div class="chat-bubble chat-bubble-info opacity-75 mb-2">
                        <div class="font-bold">{{ $message->replyTo->sender->name }}</div>
                        <div class="truncate">{{ $message->replyTo->text }}</div>
                    </div>
                @endif

                <div class="chat-bubble {{ $message->user_id === auth()->id() ? 'chat-bubble-primary' : 'chat-bubble-secondary' }} group">
                    @if($message->text)
                        <p class="whitespace-pre-wrap">{{ $message->text }}</p>
                    @endif

                    @if($message->file)
                        <div class="mt-2">
                            @if(str_starts_with($message->file->mime_type, 'image/'))
                                <img src="{{ Storage::url($message->file->url) }}" 
                                     alt="Uploaded image"
                                     class="rounded-lg max-w-sm">
                                @if($message->file->class && $message->file->class !== 'normal')
                                    <div class="mt-1 text-sm opacity-75">
                                        Classified as: {{ $message->file->class }}
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file"></i>
                                    <span>{{ $message->file->original_name }}</span>
                                    <a href="{{ Storage::url($message->file->url) }}" 
                                       download
                                       class="link link-primary">
                                        Download
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="opacity-0 group-hover:opacity-100 transition-opacity absolute {{ $message->user_id === auth()->id() ? '-left-8' : '-right-8' }} top-0 flex items-center gap-1">
                        <button wire:click="setReplyTo({{ $message->id }})" class="btn btn-circle btn-xs btn-ghost">
                            <i class="fas fa-reply"></i>
                        </button>
                        @if($message->user_id === auth()->id() || $conversation->isAdmin(auth()->user()))
                            <button wire:click="deleteMessage({{ $message->id }})" class="btn btn-circle btn-xs btn-ghost text-error">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply to message -->
    @if($replyTo)
        <div class="bg-base-200 border-t border-base-300 p-2 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-reply opacity-50 mr-2"></i>
                <div class="text-sm">
                    <span class="text-primary">{{ Message::find($replyTo)->sender->name }}</span>
                    <span class="opacity-75 truncate">{{ Message::find($replyTo)->text }}</span>
                </div>
            </div>
            <button wire:click="cancelReply" class="btn btn-circle btn-xs btn-ghost">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Message Input -->
    <div class="bg-base-100 border-t border-base-300 p-4">
        <form wire:submit="sendMessage" class="flex items-end gap-4">
            <div class="flex-1">
                <textarea wire:model="message" 
                          rows="1"
                          class="textarea textarea-bordered w-full"
                          placeholder="Type a message..."
                          @keydown.enter.prevent="$wire.sendMessage()"></textarea>
            </div>
            
            <div class="flex items-center gap-2">
                <livewire:conversation.file-upload :conversation-id="$conversation->id" />
                
                <button type="submit" class="btn btn-primary btn-circle">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Participants Sidebar -->
    @if($showParticipants)
        <div class="fixed inset-y-0 right-0 w-64 bg-base-100 border-l border-base-300 shadow-lg transform transition-transform duration-200 ease-in-out"
             x-show="showParticipants"
             @click.away="showParticipants = false">
            <div class="p-4">
                <h3 class="font-medium mb-4">
                    {{ $conversation->type === 'channel' ? 'Subscribers' : 'Participants' }}
                </h3>
                <div class="space-y-2">
                    @foreach($participants as $participant)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-8">
                                        <span>{{ substr($participant->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <span class="ml-2">{{ $participant->name }}</span>
                            </div>
                            @if($conversation->isAdmin(auth()->user()) && $participant->id !== auth()->id())
                                <button wire:click="removeParticipant({{ $participant->id }})" 
                                        class="btn btn-circle btn-xs btn-ghost text-error">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Scroll to bottom on new messages
    const messagesContainer = document.getElementById('messages-container');
    
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Scroll on initial load
    scrollToBottom();

    // Scroll when new message is added
    Livewire.on('messageSent', () => {
        scrollToBottom();
    });

    // Handle real-time updates
    window.addEventListener('livewire:initialized', () => {
        Echo.private(`conversation.${@this.conversation.id}`)
            .listen('MessageSent', (e) => {
                @this.$refresh();
                scrollToBottom();
            });
    });
</script>
@endpush 