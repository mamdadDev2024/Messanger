import { Config } from './config.js';

export async function fetchConversations() {
  const res = await fetch(`${Config.apiBase}${Config.endpoints.conversations}`);
  return res.json();
}

export async function fetchMessages(conversationId) {
  const res = await fetch(`${Config.apiBase}${Config.endpoints.conversations}/${conversationId}/messages`);
  return res.json();
}

export async function sendMessage(conversationId, formData) {
  const res = await fetch(
    `${Config.apiBase}${Config.endpoints.conversations}/${conversationId}${Config.endpoints.messages}`,
    { method: 'POST', body: formData }
  );
  return res.json();
}

export async function markAsRead(messageId) {
  await fetch(
    `${Config.apiBase}${Config.endpoints.readMessage(messageId)}`,
    { method: 'PATCH' }
  );
}
