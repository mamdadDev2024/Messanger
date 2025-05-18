import axios from 'axios';
import { Config } from './config.js';

export async function fetchMessages(conversationId, force = false) {
  const CACHE_KEY = `messages_${conversationId}`;
  if (!force) {
    const cached = localStorage.getItem(CACHE_KEY);
    if (cached) {
      try {
        return JSON.parse(cached);
      } catch {}
    }
  }
  const res = await axios.get(`${Config.apiBase}${Config.endpoints.conversations}/${conversationId}/messages`);
  localStorage.setItem(CACHE_KEY, JSON.stringify(res.data));
  return res.data;
}

// سایر متدهای مربوط به پیام (ایجاد، ویرایش، حذف) فقط با event-driven و در api.js اصلی انجام می‌شود 