import axios from 'axios';
import { Config } from './config.js';

const CACHE_KEY = 'conversations';

export async function fetchConversations(force = false) {
  if (!force) {
    const cached = localStorage.getItem(CACHE_KEY);
    if (cached) {
      try {
        return JSON.parse(cached);
      } catch {}
    }
  }
  const res = await axios.get(`${Config.apiBase}${Config.endpoints.conversations}`);
  localStorage.setItem(CACHE_KEY, JSON.stringify(res.data));
  return res.data;
}

export async function fetchConversation(conversationId) {
  const res = await axios.get(`${Config.apiBase}${Config.endpoints.conversations}/${conversationId}`);
  return res.data;
}

// سایر متدهای مربوط به گفتگو (ایجاد، ویرایش، حذف) را در صورت نیاز اضافه کن 