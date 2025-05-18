import { Config } from './config.js';
import axios from 'axios';
import { Events } from './events.js';

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

export async function sendMessageRequest(data) {
  return axios.post('/broadcasting/message', {
    event: Events.MessageSentRequest,
    ...data
  });
}

export async function updateMessageRequest(data) {
  return axios.post('/broadcasting/message', {
    event: Events.MessageUpdateRequest,
    ...data
  });
}

export async function deleteMessageRequest(data) {
  return axios.post('/broadcasting/message', {
    event: Events.MessageDeleteRequest,
    ...data
  });
}

export async function readMessageRequest(data) {
  return axios.post('/broadcasting/message', {
    event: Events.MessageReadRequest,
    ...data
  });
}
