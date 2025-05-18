<?php

use App\Models\Conversation;
use App\Models\File;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('text')->nullable();

            $table->uuid('forwarded_from_id')->nullable();
            $table->foreign('forwarded_from_id')->references('id')->on('messages')->cascadeOnDelete();

            $table->uuid('replay_to_id')->nullable();
            $table->foreign('replay_to_id')->references('id')->on('messages')->cascadeOnDelete();

            $table->uuid('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnDelete();

            $table->uuid('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('conversations')->cascadeOnDelete();

            $table->uuid('file_id')->nullable();
            $table->foreign('file_id')->references('id')->on('files')->cascadeOnDelete();

            $table->timestamp('read_at')->nullable();
            $table->string('status')->default('sent');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
