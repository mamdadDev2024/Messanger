import { fetchConversations } from './conversationApi.js';
import { fetchMessages } from './messageApi.js';
import { MessageStore } from './messageStore.js';
import { ChatChannel } from './chat.js';
import { sendMessageRequest, updateMessageRequest, deleteMessageRequest, readMessageRequest } from './api.js';

export function alpineChatComponent() {
  return {
    conversations: [],
    selectedConversation: null,
    messageStore: null,
    chatChannel: null,
    loadingConversations: false,
    loadingMessages: false,
    async init() {
      this.loadingConversations = true;
      this.conversations = await fetchConversations();
      this.loadingConversations = false;
    },
    async selectConversation(conversation) {
      this.selectedConversation = conversation;
      this.loadingMessages = true;
      this.messageStore = new MessageStore();
      // بارگذاری پیام‌ها و افزودن به messageStore
      const messages = await fetchMessages(conversation.id);
      messages.forEach(msg => this.messageStore.addMessage(msg));
      this.loadingMessages = false;
      // اتصال realtime
      if (this.chatChannel) this.chatChannel.disconnect?.();
      this.chatChannel = new ChatChannel(conversation, this.messageStore);
      this.chatChannel.connect();
    },
    get messages() {
      return this.messageStore ? this.messageStore.getMessages() : [];
    },
    get users() {
      return this.messageStore ? this.messageStore.getUsers() : [];
    },
    async sendMessage(text, file = null, reply_to_id = null, forwarded_from_id = null) {
      if (!this.selectedConversation) return;
      const data = {
        text,
        conversation_type: this.selectedConversation.type,
        conversation_id: this.selectedConversation.id,
        sender_id: this.currentUserId, // فرض بر این است که currentUserId در state Alpine ست شده باشد
        file,
        reply_to_id,
        forwarded_from_id
      };
      await sendMessageRequest(data);
    },
    async updateMessage(messageId, text, file = null) {
      if (!this.selectedConversation) return;
      const data = {
        message_id: messageId,
        conversation_type: this.selectedConversation.type,
        conversation_id: this.selectedConversation.id,
        text,
        file
      };
      await updateMessageRequest(data);
    },
    async deleteMessage(messageId) {
      if (!this.selectedConversation) return;
      const data = {
        message_id: messageId,
        conversation_type: this.selectedConversation.type,
        conversation_id: this.selectedConversation.id
      };
      await deleteMessageRequest(data);
    },
    async markMessageRead(messageId) {
      if (!this.selectedConversation || !this.currentUserId) return;
      const data = {
        message_id: messageId,
        conversation_type: this.selectedConversation.type,
        conversation_id: this.selectedConversation.id,
        user_id: this.currentUserId
      };
      await readMessageRequest(data);
    },
  };
} 