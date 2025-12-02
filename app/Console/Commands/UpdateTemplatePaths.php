<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateTemplatePaths extends Command
{
    protected $signature = 'templates:update-paths';
    protected $description = 'Update template file paths from storage';

    public function handle()
    {
        $templates = Template::all();
        
        foreach ($templates as $template) {
            $slug = Str::slug($template->name);
            $price = $template->price;
            $type = $price > 0 ? 'premium' : 'free';
            
            // Cari file di storage
            $templateDir = "templates/{$type}/{$slug}";
            
            if (Storage::exists($templateDir)) {
                // Cari index.html
                $indexFile = $this->findIndexFile($templateDir);
                $cssFile = $this->findAssetFile($templateDir, 'css');
                $jsFile = $this->findAssetFile($templateDir, 'js');
                $imagesDir = $templateDir . '/images';
                
                // Update template
                $template->update([
                    'html_file_path' => $indexFile,
                    'css_file_path' => $cssFile,
                    'js_file_path' => $jsFile,
                    'images_path' => Storage::exists($imagesDir) ? $imagesDir : null,
                ]);
                
                $this->info("Updated: {$template->name}");
            } else {
                $this->warn("Not found in storage: {$template->name}");
            }
        }
        
        $this->info('Done!');
    }
    
    private function findIndexFile($directory)
    {
        $files = [
            $directory . '/index.html',
            $directory . '/index.htm',
            $directory . '/home.html',
        ];
        
        foreach ($files as $file) {
            if (Storage::exists($file)) {
                return $file;
            }
        }
        
        return null;
    }
    
    private function findAssetFile($directory, $type)
    {
        $extensions = [
            'css' => ['css', 'scss', 'less'],
            'js' => ['js', 'jsx', 'ts'],
        ];
        
        if (!isset($extensions[$type])) {
            return null;
        }
        
        // Coba cari file utama
        $mainFiles = [
            $directory . "/main.{$type}",
            $directory . "/style.{$type}",
            $directory . "/app.{$type}",
            $directory . "/{$type}/main.{$type}",
            $directory . "/{$type}/style.{$type}",
        ];
        
        foreach ($mainFiles as $file) {
            foreach ($extensions[$type] as $ext) {
                $fileWithExt = str_replace(".{$type}", ".{$ext}", $file);
                if (Storage::exists($fileWithExt)) {
                    return $fileWithExt;
                }
            }
        }
        
        return null;
    }
}