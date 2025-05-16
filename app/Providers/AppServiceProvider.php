<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Observers\UserObServer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Message::observe(Message::class);
        Conversation::observe(Conversation::class);
        User::observe(UserObServer::class);
    }
}
