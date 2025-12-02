<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;

class TemplateAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Anda perlu membuat middleware admin
    }
    
    /**
     * Display list of templates
     */
    public function index()
    {
        $templates = Template::with('category')->latest()->paginate(20);
        
        // Stats
        $totalTemplates = Template::count();
        $freeTemplates = Template::where('price', 0)->count();
        $premiumTemplates = Template::where('price', '>', 0)->count();
        $activeTemplates = Template::where('is_active', true)->count();
        
        return view('admin.templates.index', compact(
            'templates',
            'totalTemplates',
            'freeTemplates',
            'premiumTemplates',
            'activeTemplates'
        ));
    }
    
    /**
     * Show upload form
     */
    public function uploadForm()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.templates.upload', compact('categories'));
    }
    
    /**
     * Handle template upload from ZIP file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:20480', // Max 20MB
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
        ]);
        
        DB::beginTransaction();
        
        try {
            $zipFile = $request->file('zip_file');
            $templateName = $request->name;
            $price = $request->price;
            $categoryId = $request->category_id;
            $description = $request->description;
            $isFeatured = $request->boolean('is_featured');
            $slug = Str::slug($templateName);
            
            // Cek apakah template dengan nama/slug yang sama sudah ada
            $existingTemplate = Template::where('slug', $slug)->first();
            if ($existingTemplate) {
                return back()
                    ->withInput()
                    ->with('error', "Template dengan nama '{$templateName}' sudah ada. Gunakan nama yang berbeda.");
            }
            
            // Buat folder temporary untuk extract
            $tempDir = storage_path('app/temp/' . Str::random(20));
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Simpan ZIP file sementara
            $zipPath = $zipFile->storeAs('temp', $zipFile->getClientOriginalName());
            $fullZipPath = storage_path('app/' . $zipPath);
            
            // Extract ZIP file
            $zip = new ZipArchive;
            if ($zip->open($fullZipPath) !== TRUE) {
                throw new \Exception("Tidak bisa membuka file ZIP!");
            }
            
            $zip->extractTo($tempDir);
            $zip->close();
            
            // Hapus file ZIP sementara
            unlink($fullZipPath);
            
            // Cari file index.html utama
            $indexFile = $this->findIndexFile($tempDir);
            if (!$indexFile) {
                throw new \Exception("File index.html tidak ditemukan dalam template!");
            }
            
            // Baca konten HTML
            $htmlContent = file_get_contents($indexFile);
            
            // Path untuk template di storage
            $templateType = $price > 0 ? 'premium' : 'free';
            $templateDir = "templates/{$templateType}/{$slug}";
            $storagePath = storage_path("app/{$templateDir}");
            
            // Hapus folder lama jika ada
            if (is_dir($storagePath)) {
                $this->deleteDirectory($storagePath);
            }
            
            // Pindahkan file dari temp ke permanent location
            rename($tempDir, $storagePath);
            
            // Cari file CSS dan JS
            $cssPath = $this->findAssetFile($storagePath, 'css');
            $jsPath = $this->findAssetFile($storagePath, 'js');
            
            // Extract thumbnail (ambil screenshot atau gambar pertama)
            $thumbnail = $this->extractThumbnail($storagePath, $slug);
            
            // Proses HTML untuk relative paths
            $processedHtml = $this->processHtmlPaths($htmlContent, $templateDir);
            
            // Simpan template ke database
            $template = Template::create([
                'name' => $templateName,
                'slug' => $slug,
                'description' => $description ?? "{$templateName} template untuk berbagai kebutuhan",
                'price' => $price,
                'thumbnail' => $thumbnail,
                'html_content' => $processedHtml,
                'html_file_path' => $templateDir . '/index.html',
                'css_file_path' => $cssPath,
                'js_file_path' => $jsPath,
                'images_path' => $templateDir . '/images',
                'category_id' => $categoryId,
                'is_featured' => $isFeatured,
                'is_active' => true,
                'features' => json_encode([
                    'Responsive Design',
                    'Mobile Friendly',
                    'Fast Loading',
                    'SEO Optimized',
                    'Easy to Customize'
                ]),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.templates.index')
                ->with('success', "Template '{$templateName}' berhasil diupload!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up temp directory if exists
            if (isset($tempDir) && is_dir($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupload template: ' . $e->getMessage());
        }
    }
    
    /**
     * Create template manually (tanpa ZIP)
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.templates.create', compact('categories'));
    }
    
    /**
     * Store manually created template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'html_content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        try {
            $slug = Str::slug($request->name);
            
            // Cek duplikasi
            if (Template::where('slug', $slug)->exists()) {
                return back()
                    ->withInput()
                    ->with('error', 'Template dengan nama tersebut sudah ada.');
            }
            
            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('templates/thumbnails', 'public');
            }
            
            $template = Template::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'thumbnail' => $thumbnailPath,
                'html_content' => $request->html_content,
                'category_id' => $request->category_id,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active', true),
                'features' => json_encode($request->features ?? []),
            ]);
            
            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil dibuat!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }
    
    /**
     * Edit template
     */
    public function edit($id)
    {
        $template = Template::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.templates.edit', compact('template', 'categories'));
    }
    
    /**
     * Update template
     */
    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        try {
            $slug = Str::slug($request->name);
            
            // Cek duplikasi (kecuali template ini sendiri)
            $duplicate = Template::where('slug', $slug)
                ->where('id', '!=', $id)
                ->first();
                
            if ($duplicate) {
                return back()
                    ->withInput()
                    ->with('error', 'Template dengan nama tersebut sudah ada.');
            }
            
            // Update thumbnail jika ada
            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                    Storage::disk('public')->delete($template->thumbnail);
                }
                
                $thumbnailPath = $request->file('thumbnail')->store('templates/thumbnails', 'public');
                $template->thumbnail = $thumbnailPath;
            }
            
            $template->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ]);
            
            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui template: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete template
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        
        try {
            // Cek apakah template sedang digunakan
            $userTemplateCount = UserTemplate::where('template_id', $id)->count();
            
            if ($userTemplateCount > 0) {
                return back()
                    ->with('warning', "Template ini sedang digunakan oleh {$userTemplateCount} user. Tidak bisa dihapus.");
            }
            
            // Hapus thumbnail
            if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            
            // Hapus template files dari storage
            if ($template->html_file_path && Storage::exists($template->html_file_path)) {
                $templateDir = dirname($template->html_file_path);
                Storage::deleteDirectory($templateDir);
            }
            
            $template->delete();
            
            return redirect()->route('admin.templates.index')
                ->with('success', 'Template berhasil dihapus!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }
    
    /**
     * Helper Methods
     */
    private function findIndexFile($directory)
    {
        // Cari file utama
        $possibleFiles = [
            $directory . '/index.html',
            $directory . '/index.htm',
            $directory . '/home.html',
            $directory . '/default.html',
        ];
        
        foreach ($possibleFiles as $file) {
            if (file_exists($file)) {
                return $file;
            }
        }
        
        // Cari file HTML pertama
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'html') {
                return $file->getPathname();
            }
        }
        
        return null;
    }
    
    private function findAssetFile($directory, $type)
    {
        $extensions = [
            'css' => ['css', 'scss', 'less'],
            'js' => ['js', 'jsx', 'ts', 'tsx'],
        ];
        
        if (!isset($extensions[$type])) {
            return null;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        $mainFile = null;
        $typeFiles = [];
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                
                if (in_array($ext, $extensions[$type])) {
                    $filename = $file->getFilename();
                    
                    // Prioritize main files
                    if (strpos($filename, 'main.') === 0 || 
                        strpos($filename, 'style.') === 0 || 
                        strpos($filename, 'app.') === 0) {
                        $mainFile = str_replace(storage_path('app/'), '', $file->getPathname());
                    }
                    
                    $typeFiles[] = str_replace(storage_path('app/'), '', $file->getPathname());
                }
            }
        }
        
        return $mainFile ?? ($typeFiles[0] ?? null);
    }
    
    private function extractThumbnail($directory, $slug)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Cari screenshot atau hero image
        $preferredNames = ['screenshot', 'preview', 'hero', 'banner', 'featured'];
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = strtolower($file->getFilename());
                $ext = strtolower($file->getExtension());
                
                if (in_array($ext, $imageExtensions)) {
                    // Cek apakah ini screenshot atau hero image
                    foreach ($preferredNames as $preferred) {
                        if (strpos($filename, $preferred) !== false) {
                            return $this->copyImageToPublic($file->getPathname(), $slug, $ext);
                        }
                    }
                }
            }
        }
        
        // Jika tidak ditemukan, ambil gambar pertama
        $iterator->rewind();
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $imageExtensions)) {
                    return $this->copyImageToPublic($file->getPathname(), $slug, $ext);
                }
            }
        }
        
        // Jika masih tidak ada, gunakan default
        return 'images/default-template.jpg';
    }
    
    private function copyImageToPublic($imagePath, $slug, $extension)
    {
        $publicDir = public_path('storage/templates/thumbnails');
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        
        $publicPath = "storage/templates/thumbnails/{$slug}.{$extension}";
        $fullPublicPath = public_path($publicPath);
        
        copy($imagePath, $fullPublicPath);
        
        return $publicPath;
    }
    
    private function processHtmlPaths($html, $templateDir)
    {
        // Convert relative paths to storage URLs
        $templateBasePath = str_replace('\\', '/', storage_path("app/{$templateDir}"));
        
        // Pattern untuk mencari relative paths
        $patterns = [
            '/(src|href)=["\'](?!https?:\/\/)([^"\']+)["\']/i',
            '/url\(["\']?(?!https?:\/\/)([^"\'\)]+)["\']?\)/i'
        ];
        
        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function($matches) use ($templateBasePath, $templateDir) {
                $attribute = $matches[1] ?? '';
                $relativePath = $matches[2] ?? ($matches[1] ?? '');
                
                // Bersihkan path
                $relativePath = ltrim($relativePath, './');
                
                // Coba beberapa lokasi
                $possiblePaths = [
                    $templateDir . '/' . $relativePath,
                    dirname($templateDir) . '/' . $relativePath,
                    'templates/' . basename($templateDir) . '/' . $relativePath,
                ];
                
                foreach ($possiblePaths as $path) {
                    if (Storage::exists($path)) {
                        return str_replace($relativePath, Storage::url($path), $matches[0]);
                    }
                }
                
                // Jika tidak ditemukan, biarkan asli
                return $matches[0];
            }, $html);
        }
        
        return $html;
    }
    
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        rmdir($dir);
    }
}