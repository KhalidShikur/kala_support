<?php

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
            $table->foreignId('workspace_id')->constrained('workspaces', 'id')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers', 'id')->onDelete('cascade');
            $table->foreignId('bot_id')->constrained('bots', 'id')->onDelete('cascade');
            $table->enum('status', ['closed', 'open'])->default('open');
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
