<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('default_provider')->default('openai');
            $table->string('default_model')->default('gpt-4');
            $table->float('default_temperature')->default(0.7);
            $table->integer('default_max_tokens')->default(2000);
            $table->string('ui_theme')->default('light');
            $table->json('other_settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
