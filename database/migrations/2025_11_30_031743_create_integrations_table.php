<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();  // ← PASTIKAN INI ADA
            $table->text('description');
            $table->string('status');
            $table->string('status_text');
            $table->string('icon');
            $table->string('logo_color');
            $table->string('category');
            $table->boolean('is_active')->default(false);
            $table->integer('order')->default(0);
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};