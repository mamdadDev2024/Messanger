@push('scripts')
<script src="/js/Modules/alpineChatComponent.js"></script>
@endpush
<div x-data="alpineChatComponent()" x-init="init()" class="flex h-screen">
    <!-- سایدبار گفتگوها -->
    <aside class="w-1/4 bg-gray-100 p-4 overflow-y-auto">
        <template x-for="conv in conversations" :key="conv.id">
            <button @click="selectConversation(conv)"
                class="block w-full text-right p-2 rounded hover:bg-blue-100"
                :class="{'bg-blue-200': selectedConversation && selectedConversation.id === conv.id}">
                <span x-text="conv.name"></span>
                <span class="text-xs text-gray-500" x-text="conv.type"></span>
            </button>
        </template>
    </aside>
    <!-- بخش پیام‌ها -->
    <main class="flex-1 flex flex-col bg-white">
        <div class="flex-1 overflow-y-auto p-4">
            <template x-if="loadingMessages">
                <div class="text-center text-gray-400">در حال بارگذاری پیام‌ها...</div>
            </template>
            <template x-for="msg in messages" :key="msg.id">
                <div class="mb-2 flex" :class="{'justify-end': msg.sender_id === currentUserId}">
                    <div class="max-w-xs p-2 rounded shadow"
                        :class="msg.sender_id === currentUserId ? 'bg-blue-100' : 'bg-gray-100'">
                        <div class="text-xs text-gray-500" x-text="msg.sender?.user_name"></div>
                        <div x-text="msg.text"></div>
                        <template x-if="msg.file && msg.file.mime_type?.startsWith('image/')">
                            <img :src="msg.file.url"
                                 class="mt-2 rounded cursor-pointer transition-all duration-200"
                                 :class="msg.file.class === 'blur' ? 'blur-sm' : ''"
                                 @click="/* نمایش بزرگنمایی */">
                        </template>
                        <div class="flex gap-2 mt-1">
                            <button @click="deleteMessage(msg.id)" class="text-xs text-red-500">حذف</button>
                            <button @click="updateMessage(msg.id, 'متن جدید')" class="text-xs text-blue-500">ویرایش</button>
                            <button @click="markMessageRead(msg.id)" class="text-xs text-green-500">خوانده شد</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <!-- فرم ارسال پیام -->
        <form @submit.prevent="sendMessage($refs.text.value)" class="flex p-2 border-t">
            <input x-ref="text" type="text" class="flex-1 border rounded px-2 py-1" placeholder="پیام...">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-1 rounded">ارسال</button>
        </form>
    </main>
</div> 