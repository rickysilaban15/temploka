<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            // Hanya tambahkan html_content karena kolom lainnya sudah ada
            if (!Schema::hasColumn('templates', 'html_content')) {
                $table->longText('html_content')->nullable()->after('html_file_path');
            }
            
            // Tambahkan kolom lainnya yang disebutkan di migrasi kedua
            $additionalColumns = [
                'preview_url' => ['type' => 'string', 'nullable' => true],
                'version' => ['type' => 'string', 'default' => '1.0', 'nullable' => false],
                'framework' => ['type' => 'string', 'nullable' => true],
                'browser_compatibility' => ['type' => 'string', 'nullable' => true],
                'seo_score' => ['type' => 'integer', 'nullable' => true],
            ];
            
            foreach ($additionalColumns as $column => $options) {
                if (!Schema::hasColumn('templates', $column)) {
                    if ($options['type'] === 'string') {
                        $col = $table->string($column);
                        if (isset($options['default'])) {
                            $col->default($options['default']);
                        }
                        if (isset($options['nullable']) && $options['nullable']) {
                            $col->nullable();
                        }
                    } elseif ($options['type'] === 'integer') {
                        $col = $table->integer($column);
                        if (isset($options['nullable']) && $options['nullable']) {
                            $col->nullable();
                        }
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan oleh migrasi ini
            $columns = [
                'html_content',
                'preview_url',
                'version',
                'framework',
                'browser_compatibility',
                'seo_score'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};