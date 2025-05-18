@script
<script>
    Alpine.data('chatApp', () => ({
      darkMode: false,
      conversations: [],
      activeConversation: null,
      newMessage: '',
      file: null,

      init() {
        // For testing purposes, you can add dummy data:
        this.conversations = [
          {
            id: 1,
            name: 'علی',
            avatar: 'https://i.pravatar.cc/50?img=1',
            status: 'online',
            unreadCount: 2,
            lastMessagePreview: 'سلام، چطوری؟',
            messages: []
          }
        ];

        this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
      },

      toggleDarkMode() {
        this.darkMode = !this.darkMode;
      },

      selectConversation(id) {
        const conv = this.conversations.find(c => c.id === id);
        if (conv) {
          // Ensure it has a messages array
          if (!Array.isArray(conv.messages)) {
            conv.messages = [];
          }
          this.activeConversation = conv;
          this.scrollToBottom();
        }
      },

      closeConversation() {
        this.activeConversation = null;
      },

      sendMessage() {
        if (!this.activeConversation) return;

        const messageContent = this.newMessage.trim();

        if (messageContent) {
          this.activeConversation.messages.push({
            id: Date.now(),
            from: 'me',
            type: 'text',
            content: messageContent
          });
          this.newMessage = '';
          this.scrollToBottom();
        } else if (this.file) {
          const url = URL.createObjectURL(this.file);
          this.activeConversation.messages.push({
            id: Date.now(),
            from: 'me',
            type: this.file.type.startsWith('image/') ? 'image' : 'file',
            content: url,
            fileName: this.file.name
          });
          this.file = null;

          if (this.$refs.fileInput) {
            this.$refs.fileInput.value = null;
          }

          this.scrollToBottom();
        }
      },

      handleFileUpload(event) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
          this.file = selectedFile;
        }
      },

      triggerFile() {
        if (this.$refs.fileInput) {
          this.$refs.fileInput.click();
        }
      },

      openLightbox(src) {
        alert('Open: ' + src); // You can replace this with actual modal logic
      },

      scrollToBottom() {
        this.$nextTick(() => {
          if (this.$refs.msgContainer) {
            this.$refs.msgContainer.scrollTop = this.$refs.msgContainer.scrollHeight;
          }
        });
      }
    }))
</script>
@endscript

<div x-data="chatApp()" :class="darkMode ? 'dark' : ''" class="flex h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
  <!-- Layout Container -->
  <div class="flex flex-1">
    <!-- Sidebar -->
    <aside class="w-20 md:w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transition-transform duration-300">
      <div class="p-4 flex items-center justify-between">
        <span class="text-xl font-bold hidden md:block">گفتگوها</span>
        <button @click="toggleDarkMode" class="md:hidden p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          <template x-if="!darkMode">
            🌙
          </template>
          <template x-if="darkMode">
            ☀️
          </template>
        </button>
      </div>
      <nav class="flex-1 overflow-y-auto px-2">
        <template x-if="conversations.length">
          <ul class="space-y-1">
            <template x-for="conv in conversations" :key="conv.id">
              <li
                @click="selectConversation(conv.id)"
                :class="{'bg-gray-200 dark:bg-gray-700': activeConversation && activeConversation.id === conv.id}"
                class="cursor-pointer flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
              >
                <img :src="conv.avatar" class="w-8 h-8 rounded-full" alt="Avatar">
                <div class="flex-1 mr-2 hidden md:block">
                  <h3 class="truncate font-medium" x-text="conv.name"></h3>
                  <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="conv.lastMessagePreview"></p>
                </div>
                <template x-if="conv.unreadCount">
                  <span class="bg-blue-500 text-white text-xs rounded-full px-2 transition-transform transform hover:scale-110" x-text="conv.unreadCount"></span>
                </template>
              </li>
            </template>
          </ul>
        </template>
        <template x-if="!conversations.length">
          <div class="p-4 text-center text-gray-500 dark:text-gray-400">هیچ گفتگویی وجود ندارد.</div>
        </template>
      </nav>
      <button @click="toggleDarkMode" class="mt-auto m-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 hidden md:block">
        <template x-if="!darkMode">🌙</template>
        <template x-if="darkMode">☀️</template>
      </button>
    </aside>

    <!-- Main Chat -->
    <main class="flex-1 flex flex-col">
      <!-- Chat Header -->
      <header x-show="activeConversation" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
              class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3 rtl:space-x-reverse">
          <img :src="activeConversation.avatar" class="w-10 h-10 rounded-full" alt="Avatar">
          <div>
            <h2 class="font-semibold" x-text="activeConversation.name"></h2>
            <p class="text-sm" :class="activeConversation.status === 'online' ? 'text-green-500' : 'text-gray-500 dark:text-gray-400'" x-text="activeConversation.status === 'online' ? 'آنلاین' : 'آفلاین'"></p>
          </div>
        </div>
        <button @click="closeConversation()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
          &#x274C;
        </button>
      </header>

      <!-- Messages -->
      <div x-show="activeConversation" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
           class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="msgContainer">
        <template x-for="msg in activeConversation.messages" :key="msg.id">
          <div :class="msg.from === 'me' ? 'justify-end flex' : 'justify-start flex'" class="transition-colors duration-200">
            <div :class="msg.from === 'me' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100'"
                 class="p-3 rounded-2xl max-w-xs transform transition-transform duration-200 hover:scale-105">
              <template x-if="msg.type === 'text'">
                <p x-text="msg.content"></p>
              </template>
              <template x-if="msg.type === 'image'">
                <img :src="msg.content" @click="openLightbox(msg.content)" class="rounded cursor-pointer max-w-full transition-transform transform hover:scale-110" />
              </template>
              <template x-if="msg.type === 'file'">
                <a :href="msg.content" download class="underline hover:text-blue-600" x-text="msg.fileName"></a>
              </template>
            </div>
          </div>
        </template>
        <div x-show="!activeConversation.messages.length" class="text-center text-gray-500 dark:text-gray-400">پیامی موجود نیست.</div>
      </div>

      <!-- No Active -->
      <div x-show="!activeConversation" class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400">
        گفتگویی انتخاب نشده است.
      </div>

      <!-- Input Footer -->
      <footer x-show="activeConversation" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
              class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex items-center space-x-2 rtl:space-x-reverse">
        <button @click="triggerFile()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors" title="پیوست">📎</button>
        <input type="text" x-model="newMessage" @keydown.enter.prevent="sendMessage()"
               placeholder="پیام خود را بنویسید..."
               class="flex-1 px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors duration-200">
        <button @click="sendMessage()" class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors">ارسال</button>
        <input type="file" x-ref="fileInput" @change="handleFileUpload" class="hidden">
      </footer>
    </main>
  </div>
</div>
