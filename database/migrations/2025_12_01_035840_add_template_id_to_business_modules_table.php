<?php
// database/migrations/2025_12_01_xxxxxx_add_template_id_to_business_modules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('business_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('business_modules', 'template_id')) {
                $table->foreignId('template_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('business_modules', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });
    }
};