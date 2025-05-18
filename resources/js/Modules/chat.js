
this.channel = window.Echo.join(this.conversation.channelName)
    .here(users => {
        this.currentUsers = users;
        this.updateUsersList();
    })
    .joining(user => this.handleUserJoining(user))
    .leaving(user  => this.handleUserLeaving(user))
    .listenForWhisper('typing',      user => this.handleUserTyping(user))
    .listen('send-message',data => this.handleNewMessage(data))
    .listen('update-message',data => this.handleNewMessage(data))
    .listen('delete-message',data => this.handleNewMessage(data))
    .listen('read-message',data => this.handleNewMessage(data))