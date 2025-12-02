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
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // GANTI 'title' MENJADI 'name'
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('instructor_name'); // GANTI 'instructor' MENJADI 'instructor_name'
            $table->string('instructor_title');
            $table->string('instructor_avatar')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_live')->default(false);
            $table->boolean('is_recorded')->default(false);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration_minutes');
            $table->integer('max_participants');
            $table->integer('current_participants')->default(0);
            $table->string('status');
            $table->string('button_text');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->string('category');
            $table->string('level');
            $table->integer('order')->default(0);
            $table->string('meeting_url')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinars');
    }
};