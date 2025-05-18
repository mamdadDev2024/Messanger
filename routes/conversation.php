<?php

use App\Livewire\Conversation\Group\Create;
use App\Livewire\Conversation\Show;
use App\Livewire\Conversation\Group\Update;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

Route::prefix('g')->as('group.')->group(function () {
    Route::get('create' , Create::class)->name('create');
    Route::get('s/{token}' , Show::class)->name('show');
    Route::get('update' , Update::class)->name('update');
});

Route::prefix('c')->as('channel.')->group(function () {
    Route::get('create' , Create::class)->name('create');
    Route::get('s/{token}' , Show::class)->name('show');
    Route::get('update' , Update::class)->name('update');
});

Route::prefix('p')->as('private.')->group(function () {
    Route::get('s/{token}' , Show::class)->name('show');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
});
