<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Pro, Business
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('billing_period', ['monthly', 'yearly'])->default('monthly');
            $table->integer('discount_percentage')->default(0);
            $table->json('features'); // Array of features
            $table->integer('template_limit')->nullable(); // null = unlimited
            $table->integer('storage_gb')->nullable();
            $table->boolean('priority_support')->default(false);
            $table->boolean('custom_domain')->default(false);
            $table->boolean('white_label')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};