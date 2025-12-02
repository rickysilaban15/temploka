<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webinar_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('registered_at');
            $table->timestamp('attended_at')->nullable();
            $table->boolean('has_certificate')->default(false);
            $table->timestamps();

            $table->unique(['webinar_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinar_registrations');
    }
};