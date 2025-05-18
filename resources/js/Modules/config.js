export const Config = {
  apiBase: '/api',
  endpoints: {
    conversations: '/conversations',
    messages: '/messages',
    readMessage: msgId => `/messages/${msgId}/read`,
  },
  echoChannel: (type , name) => `conversation.${type}.${name}`,
};