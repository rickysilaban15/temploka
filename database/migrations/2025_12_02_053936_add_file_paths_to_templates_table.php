<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('html_file_path')->nullable()->after('thumbnail');
            $table->string('css_file_path')->nullable()->after('html_file_path');
            $table->string('js_file_path')->nullable()->after('css_file_path');
            $table->string('images_path')->nullable()->after('js_file_path');
            
            // Hapus line ini karena kolom html_content tidak ada:
            // $table->text('html_content')->nullable()->change();
            
            // Sebagai gantinya, tambahkan kolom html_content jika memang diperlukan:
            if (!Schema::hasColumn('templates', 'html_content')) {
                $table->text('html_content')->nullable()->after('html_file_path');
            }
        });
    }

    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn([
                'html_file_path', 
                'css_file_path', 
                'js_file_path', 
                'images_path',
                'html_content' // tambahkan ini jika Anda menambahkan kolom html_content
            ]);
        });
    }
};