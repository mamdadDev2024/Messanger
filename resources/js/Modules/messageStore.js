export class MessageStore {
  constructor() {
    this.messages = [];
    this.users = [];
  }

  setUsers(users) {
    this.users = users;
  }

  addUser(user) {
    if (!this.users.find(u => u.id === user.id)) {
      this.users.push(user);
    }
  }

  removeUser(user) {
    this.users = this.users.filter(u => u.id !== user.id);
  }

  addMessage(message) {
    if (!this.messages.find(m => m.id === message.id)) {
      this.messages.push(message);
    }
  }

  updateMessage(message) {
    const idx = this.messages.findIndex(m => m.id === message.id);
    if (idx !== -1) {
      this.messages[idx] = { ...this.messages[idx], ...message };
    }
  }

  deleteMessage(messageId) {
    this.messages = this.messages.filter(m => m.id !== messageId);
  }

  markMessageRead(messageId, userId) {
    const msg = this.messages.find(m => m.id === messageId);
    if (msg) {
      if (!msg.readers) msg.readers = [];
      if (!msg.readers.includes(userId)) {
        msg.readers.push(userId);
      }
    }
  }

  updateFile(file) {
    // فایل را در پیام مربوطه بروزرسانی کن
    this.messages.forEach(msg => {
      if (msg.file_id === file.id && msg.file) {
        msg.file = { ...msg.file, ...file };
      }
    });
  }

  getMessages() {
    return this.messages;
  }

  getUsers() {
    return this.users;
  }
} 