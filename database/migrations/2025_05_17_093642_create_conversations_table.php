<?php

use App\Models\File;
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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('token')->unique()->nullable();
            $table->text('bio')->nullable();
            $table->foreignIdFor(File::class)->constrained("files")->cascadeOnDelete();
            $table->json('settings');
            $table->json('details');
            $table->enum('type' , ['private' , 'group' , 'channel'])->default('private');
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
