<?php
// database/migrations/2025_12_01_xxxxxx_add_activated_at_to_user_templates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('user_templates', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('user_templates', function (Blueprint $table) {
            $table->dropColumn('activated_at');
        });
    }
};