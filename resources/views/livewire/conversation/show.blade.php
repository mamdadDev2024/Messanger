<div 
    x-data="{
        showParticipants: @entangle('showParticipants'),
        isScrollingUp: false,
        lastScrollPosition: 0,
        handleScroll() {
            const currentPosition = this.$refs.messagesContainer.scrollTop;
            this.isScrollingUp = currentPosition < this.lastScrollPosition;
            this.lastScrollPosition = currentPosition;
        },
        shouldAutoScroll() {
            const container = this.$refs.messagesContainer;
            return container.scrollHeight - container.clientHeight - container.scrollTop < 100;
        }
    }"
    @scroll.window.debounce="handleScroll"
    class="flex flex-col h-full bg-gray-50 dark:bg-gray-950"
>
    <!-- Header -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 p-4 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center">
            <div 
                class="flex items-center justify-center rounded-full w-10 h-10 bg-gradient-to-br 
                    @if($conversation->type === 'group') from-purple-500 to-blue-500
                    @elseif($conversation->type === 'channel') from-orange-500 to-red-500
                    @else from-green-500 to-teal-500 @endif
                    text-white shadow-sm">
                @if($conversation->type === 'group')
                    <i class="fas fa-users text-sm"></i>
                @elseif($conversation->type === 'channel')
                    <i class="fas fa-bullhorn text-sm"></i>
                @else
                    <span class="font-medium">{{ substr($conversation->participants->first()->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="ml-3">
                <h2 class="font-semibold text-gray-900 dark:text-gray-100">
                    @if($conversation->type === 'private')
                        {{ $conversation->participants->first()->name }}
                    @else
                        {{ $conversation->name }}
                    @endif
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    @if($conversation->type === 'group')
                        {{ $conversation->participants->count() }} عضو
                    @elseif($conversation->type === 'channel')
                        {{ $conversation->subscribers->count() }} دنبال‌کننده
                    @else
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full 
                                @if($conversation->participants->first()->status === 'online') bg-green-500
                                @else bg-gray-400 @endif mr-1"></span>
                            {{ $conversation->participants->first()->status }}
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($conversation->type !== 'private')
                <button 
                    @click="showParticipants = !showParticipants"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200 text-gray-600 dark:text-gray-400"
                    :class="{ 'bg-gray-100 dark:bg-gray-800': showParticipants }"
                >
                    <i class="fas fa-users text-sm"></i>
                </button>
            @endif
            
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200 text-gray-600 dark:text-gray-400"
                >
                    <i class="fas fa-ellipsis-v text-sm"></i>
                </button>
                <ul 
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-20"
                >
                    <li>
                        <a 
                            wire:click="leaveConversation"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-2"
                        >
                            <i class="fas fa-sign-out-alt text-gray-500"></i>
                            خروج از گفتگو
                        </a>
                    </li>
                    @if($conversation->isAdmin(auth()->user()))
                        <li class="border-t border-gray-200 dark:border-gray-700">
                            <a 
                                wire:click="deleteConversation"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer flex items-center gap-2"
                            >
                                <i class="fas fa-trash"></i>
                                حذف گفتگو
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div 
        x-ref="messagesContainer"
        class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-950 transition-all duration-300 scroll-smooth"
        id="messages-container"
    >
        @foreach($messages as $message)
            <div 
                wire:key="msg-{{ $message->id }}"
                x-intersect:enter="$el.classList.add('opacity-100', 'translate-y-0')"
                class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }} opacity-0 translate-y-4 transition-all duration-300 ease-out"
            >
                <div class="max-w-lg w-fit">
                    @if($message->user_id !== auth()->id())
                        <div class="flex items-center mb-1">
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $message->sender->name }}</span>
                            <time class="text-xs text-gray-400 dark:text-gray-500 ml-2">{{ $message->created_at->format('H:i') }}</time>
                        </div>
                    @endif
                    
                    @if($message->replyTo)
                        <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-lg px-3 py-1 mb-2 text-xs border border-blue-200 dark:border-blue-800">
                            <span class="font-bold">{{ $message->replyTo->sender->name }}</span>
                            <span class="truncate">{{ Str::limit($message->replyTo->text, 50) }}</span>
                        </div>
                    @endif
                    
                    <div class="relative group">
                        <div 
                            class="rounded-2xl px-4 py-2 shadow-sm transition-all duration-200
                                {{ $message->user_id === auth()->id() 
                                    ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white' 
                                    : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700' }}"
                        >
                            @if($message->text)
                                <p class="whitespace-pre-wrap">{{ $message->text }}</p>
                            @endif
                            
                            @if($message->file)
                                <div class="mt-2">
                                    @if(str_starts_with($message->file->mime_type, 'image/'))
                                        <div class="relative">
                                            <img 
                                                src="{{ Storage::url($message->file->url) }}" 
                                                alt="Uploaded image" 
                                                class="rounded-lg max-w-xs cursor-pointer"
                                                @click="$dispatch('img-viewer', { src: '{{ Storage::url($message->file->url) }}' })"
                                            >
                                            @if($message->file->class && $message->file->class !== 'normal')
                                                <div class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                                    دسته‌بندی: {{ $message->file->class }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                                <i class="fas fa-file text-blue-500"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium truncate">{{ $message->file->original_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->file->sizeForHumans() }}</p>
                                            </div>
                                            <a 
                                                href="{{ Storage::url($message->file->url) }}" 
                                                download 
                                                class="p-2 text-blue-500 hover:text-blue-700 dark:hover:text-blue-400"
                                            >
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div 
                            class="absolute top-0 {{ $message->user_id === auth()->id() ? '-left-10' : '-right-10' }} 
                                opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center gap-1"
                        >
                            <button 
                                wire:click="setReplyTo({{ $message->id }})"
                                class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400"
                                title="پاسخ"
                            >
                                <i class="fas fa-reply text-xs"></i>
                            </button>
                            @if($message->user_id === auth()->user()->id || $conversation->isAdmin(auth()->user()))
                                <button 
                                    wire:click="deleteMessage({{ $message->id }})"
                                    class="p-1.5 rounded-full hover:bg-red-100 dark:hover:bg-red-900 text-red-500"
                                    title="حذف"
                                >
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if($message->user_id === auth()->id())
                        <div class="flex justify-end mt-1">
                            <time class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->format('H:i') }}</time>
                            @if($message->read_at)
                                <span class="text-xs text-blue-500 ml-1" title="خوانده شده در {{ $message->read_at->format('Y-m-d H:i') }}">
                                    <i class="fas fa-check-double"></i>
                                </span>
                            @elseif($message->created_at->diffInSeconds() < 5)
                                <span class="text-xs text-gray-400 ml-1 animate-pulse" title="در حال ارسال...">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                </span>
                            @else
                                <span class="text-xs text-gray-400 ml-1" title="ارسال شده">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- New messages indicator -->
    <div 
        x-show="isScrollingUp && !shouldAutoScroll()"
        @click="$refs.messagesContainer.scrollTop = $refs.messagesContainer.scrollHeight"
        class="sticky bottom-20 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-3 py-1 rounded-full text-xs shadow-lg cursor-pointer hover:bg-blue-600 transition-colors duration-200 z-10"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
    >
        پیام‌های جدید
        <i class="fas fa-arrow-down ml-1"></i>
    </div>

    <!-- Reply to message -->
    @if($replyTo)
        <div 
            class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-3 flex items-center justify-between sticky bottom-16"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
        >
            <div class="flex items-center truncate">
                <i class="fas fa-reply text-gray-500 mr-2"></i>
                <div class="text-sm truncate">
                    <span class="font-medium text-blue-600 dark:text-blue-400">{{ $message->replyTo->sender->name }}</span>
                    <span class="text-gray-600 dark:text-gray-400 truncate">{{ Str::limit($message->replyTo->text, 40) }}</span>
                </div>
            </div>
            <button 
                wire:click="cancelReply"
                class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Message Input -->
    <div class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-4 sticky bottom-0">
        <form wire:submit.prevent="sendMessage" class="flex items-end gap-3">
            <div class="flex-1 relative">
                <textarea 
                    wire:model.live.debounce.300ms="message"
                    x-ref="messageInput"
                    rows="1"
                    @input="$refs.messageInput.style.height = 'auto'; $refs.messageInput.style.height = $refs.messageInput.scrollHeight + 'px';"
                    @keydown.enter.prevent="$wire.sendMessage()"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 resize-none scroll-p-2 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="پیام خود را بنویسید..."
                ></textarea>
            </div>
            <div class="flex items-center gap-2">
                <livewire:conversation.file-upload :conversation-id="$conversation->id" />
                <button 
                    type="submit"
                    :disabled="!message.trim()"
                    class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-full transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Participants Sidebar -->
    <div 
        x-show="showParticipants"
        @click.outside="showParticipants = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed inset-y-0 right-0 w-72 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 shadow-2xl z-20"
    >
        <div class="p-4 h-full flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                    {{ $conversation->type === 'channel' ? 'دنبال‌کنندگان' : 'اعضا' }}
                    <span class="text-sm text-gray-500">({{ $participants->count() }})</span>
                </h3>
                <button 
                    @click="showParticipants = false"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            @if($conversation->type === 'group' && $conversation->isAdmin(auth()->user()))
                <div class="mb-4">
                    <button 
                        wire:click="openAddParticipantsModal"
                        class="w-full py-2 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-300 transition-colors duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-user-plus"></i>
                        افزودن عضو جدید
                    </button>
                </div>
            @endif
            
            <div class="flex-1 overflow-y-auto space-y-2">
                @foreach($participants as $participant)
                    <div 
                        class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
                        :class="{ 'bg-gray-100 dark:bg-gray-800': '{{ $participant->id }}' === '{{ auth()->id() }}' }"
                    >
                        <div class="flex items-center">
                            <div class="flex items-center justify-center rounded-full w-9 h-9 bg-gradient-to-br from-purple-500 to-blue-500 text-white font-medium">
                                {{ substr($participant->name, 0, 1) }}
                            </div>
                            <div class="mr-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $participant->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                    <span class="w-2 h-2 rounded-full 
                                        @if($participant->status === 'online') bg-green-500
                                        @else bg-gray-400 @endif mr-1"></span>
                                    {{ $participant->status }}
                                </p>
                            </div>
                        </div>
                        @if($conversation->isAdmin(auth()->user()) && $participant->id !== auth()->id())
                            <div class="flex items-center gap-1">
                                @if($participant->pivot->is_admin)
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">مدیر</span>
                                @else
                                    <button 
                                        wire:click="promoteToAdmin({{ $participant->id }})"
                                        class="p-1.5 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-500"
                                        title="ارتقا به مدیر"
                                    >
                                        <i class="fas fa-crown text-xs"></i>
                                    </button>
                                @endif
                                <button 
                                    wire:click="removeParticipant({{ $participant->id }})"
                                    class="p-1.5 rounded-full hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500"
                                    title="حذف کاربر"
                                >
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>