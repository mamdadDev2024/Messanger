import { Events } from './events.js';

export class ChatChannel {
  constructor(conversation, messageStore) {
    this.conversation = conversation;
    this.channel = null;
    this.messageStore = messageStore; // یک آبجکت یا reactive store برای مدیریت پیام‌ها
  }

  connect() {
    this.channel = window.Echo.join(this.conversation.channelName)
      .here(users => this.handleUsers(users))
      .joining(user => this.handleUserJoining(user))
      .leaving(user => this.handleUserLeaving(user))
      .listen(Events.MessageSent, data => this.handleMessageSent(data))
      .listen(Events.MessageUpdated, data => this.handleMessageUpdated(data))
      .listen(Events.MessageDeleted, data => this.handleMessageDeleted(data))
      .listen(Events.MessageRead, data => this.handleMessageRead(data))
      .listen(Events.FileClassified, data => this.handleFileClassified(data));
  }

  // متدهای هندلینگ فقط مسئول بروزرسانی UI یا state هستند
  handleUsers(users) {
    // بروزرسانی لیست کاربران حاضر در گفتگو
    if (this.messageStore && this.messageStore.setUsers) {
      this.messageStore.setUsers(users);
    }
  }
  handleUserJoining(user) {
    // افزودن کاربر به لیست کاربران حاضر
    if (this.messageStore && this.messageStore.addUser) {
      this.messageStore.addUser(user);
    }
  }
  handleUserLeaving(user) {
    // حذف کاربر از لیست کاربران حاضر
    if (this.messageStore && this.messageStore.removeUser) {
      this.messageStore.removeUser(user);
    }
  }
  handleMessageSent({ message }) {
    // افزودن پیام جدید به لیست پیام‌ها
    if (this.messageStore && this.messageStore.addMessage) {
      this.messageStore.addMessage(message);
    }
  }
  handleMessageUpdated({ message }) {
    // بروزرسانی پیام ویرایش‌شده در لیست پیام‌ها
    if (this.messageStore && this.messageStore.updateMessage) {
      this.messageStore.updateMessage(message);
    }
  }
  handleMessageDeleted({ message_id }) {
    // حذف پیام از لیست پیام‌ها
    if (this.messageStore && this.messageStore.deleteMessage) {
      this.messageStore.deleteMessage(message_id);
    }
  }
  handleMessageRead({ message_id, user_id }) {
    // بروزرسانی وضعیت خوانده‌شدن پیام برای کاربر
    if (this.messageStore && this.messageStore.markMessageRead) {
      this.messageStore.markMessageRead(message_id, user_id);
    }
  }
  handleFileClassified({ file }) {
    // بروزرسانی وضعیت فایل (مثلاً بلور کردن تصویر)
    if (this.messageStore && this.messageStore.updateFile) {
      this.messageStore.updateFile(file);
    }
  }
}