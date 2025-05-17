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
            $table->id();
            $table->text('text')->nullable();
            $table->foreignIdFor(Message::class , 'forwarded_from_id')->nullable()->constrained('messages')->nullOnDelete();
            $table->foreignIdFor(Message::class , 'reply_to_id')->nullable()->constrained('messages')->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained("users")->cascadeOnDelete();
            $table->foreignIdFor(Conversation::class)->constrained("conversations")->cascadeOnDelete();
            $table->foreignIdFor(File::class)->constrained("files")->cascadeOnDelete();
            $table->boolean('is_read')->default(false);
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
