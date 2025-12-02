<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Template;
use App\Models\Category;
use ZipArchive;

class UploadTemplate extends Command
{
    protected $signature = 'template:upload {zip_file} {--name=} {--price=0} {--category=bisnis}';
    protected $description = 'Upload template from ZIP file';

    public function handle()
    {
        $zipFile = $this->argument('zip_file');
        $templateName = $this->option('name') ?? pathinfo($zipFile, PATHINFO_FILENAME);
        $price = $this->option('price');
        $categorySlug = $this->option('category');
        
        if (!file_exists($zipFile)) {
            $this->error("File {$zipFile} tidak ditemukan!");
            return;
        }
        
        // Cek apakah template sudah ada
        $existingTemplate = Template::where('slug', Str::slug($templateName))->first();
        if ($existingTemplate) {
            if (!$this->confirm("Template '{$templateName}' sudah ada. Update?")) {
                return;
            }
        }
        
        // Buka ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipFile) !== TRUE) {
            $this->error("Tidak bisa membuka file ZIP!");
            return;
        }
        
        // Extract ke folder temporary
        $tempDir = storage_path('app/temp/' . Str::random(10));
        mkdir($tempDir, 0755, true);
        $zip->extractTo($tempDir);
        $zip->close();
        
        // Cari file index.html
        $indexFile = $this->findIndexFile($tempDir);
        if (!$indexFile) {
            $this->error("File index.html tidak ditemukan dalam template!");
            $this->deleteDirectory($tempDir);
            return;
        }
        
        // Baca konten HTML
        $htmlContent = file_get_contents($indexFile);
        
        // Extract thumbnail (ambil gambar pertama)
        $thumbnail = $this->extractThumbnail($tempDir, $templateName);
        
        // Pindahkan template ke folder permanen
        $templateDir = "templates/" . ($price > 0 ? 'premium/' : 'free/') . Str::slug($templateName);
        $storagePath = storage_path("app/{$templateDir}");
        
        // Hapus folder lama jika ada
        if (is_dir($storagePath)) {
            $this->deleteDirectory($storagePath);
        }
        
        // Pindahkan file
        rename($tempDir, $storagePath);
        
        // Simpan ke database
        $category = Category::where('slug', $categorySlug)->first();
        
        Template::updateOrCreate(
            ['slug' => Str::slug($templateName)],
            [
                'name' => $templateName,
                'slug' => Str::slug($templateName),
                'description' => $templateName . ' - Template professional untuk berbagai kebutuhan',
                'price' => $price,
                'thumbnail' => $thumbnail,
                'html_content' => $htmlContent,
                'html_file_path' => $templateDir . '/index.html',
                'css_file_path' => $this->findCssFile($storagePath),
                'js_file_path' => $this->findJsFile($storagePath),
                'images_path' => $templateDir . '/images',
                'category_id' => $category->id ?? 1,
                'is_featured' => $price > 0 ? true : false,
                'is_active' => true,
                'features' => json_encode([
                    'Responsive Design',
                    'Mobile Friendly',
                    'Fast Loading',
                    'SEO Optimized',
                    'Easy to Customize'
                ]),
            ]
        );
        
        $this->info("Template '{$templateName}' berhasil diupload!");
        $this->info("Price: Rp " . number_format($price, 0, ',', '.'));
        $this->info("Location: {$storagePath}");
    }
    
    private function findIndexFile($directory)
    {
        $files = [
            $directory . '/index.html',
            $directory . '/index.htm',
            $directory . '/home.html',
        ];
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                return $file;
            }
        }
        
        // Cari semua file HTML
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'html') {
                return $file->getPathname();
            }
        }
        
        return null;
    }
    
    private function extractThumbnail($directory, $templateName)
    {
        // Cari file gambar
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $imageExtensions)) {
                    // Salin ke public
                    $publicPath = "storage/templates/thumbnails/" . Str::slug($templateName) . ".{$ext}";
                    $fullPath = public_path($publicPath);
                    
                    // Buat folder jika belum ada
                    if (!is_dir(dirname($fullPath))) {
                        mkdir(dirname($fullPath), 0755, true);
                    }
                    
                    copy($file->getPathname(), $fullPath);
                    return $publicPath;
                }
            }
        }
        
        // Jika tidak ada gambar, gunakan default
        return 'images/default-template.jpg';
    }
    
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}