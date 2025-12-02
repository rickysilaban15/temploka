<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use App\Models\UserTemplate;
use App\Models\PricingPlan;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TemplateController extends Controller
{
    public function index()
{
    // Ambil template featured
    $featuredTemplates = Template::with('category')
        ->where('is_active', true)
        ->where('is_featured', true)
        ->latest()
        ->get();

    // SELALU ambil minimal 6 template
    $templatesCount = $featuredTemplates->count();
    $templatesNeeded = 6;
    
    if ($templatesCount >= $templatesNeeded) {
        // Jika sudah ada 6 atau lebih featured, ambil 6 terbaru
        $featuredTemplates = $featuredTemplates->take($templatesNeeded);
    } else {
        // Jika kurang dari 6, ambil template non-featured untuk melengkapi
        $needed = $templatesNeeded - $templatesCount;
        
        $additionalTemplates = Template::with('category')
            ->where('is_active', true)
            ->where('is_featured', false)
            ->latest()
            ->take($needed)
            ->get();
        
        $featuredTemplates = $featuredTemplates->merge($additionalTemplates);
    }

    // Cek template yang sudah dimiliki user
    $userTemplates = [];
    if (auth()->check()) {
        $userTemplates = UserTemplate::where('user_id', auth()->id())
            ->pluck('template_id')
            ->toArray();
    }

    $categories = Category::where('is_active', true)
        ->pluck('name', 'id')
        ->toArray();

    return view('templates.index', compact('featuredTemplates', 'userTemplates', 'categories'));
}

    public function browse(Request $request)
    {
        $query = Template::with('category')->where('is_active', true);
        
        // Filter berdasarkan plan/price range
        if ($request->has('price_min') && $request->has('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }
        
        // Filter berdasarkan plan slug
        if ($request->has('plan')) {
            $priceRange = $this->getPriceRangeForPlan($request->plan);
            if ($priceRange) {
                $query->whereBetween('price', [$priceRange['min'], $priceRange['max']]);
            }
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('id', $request->category)
                  ->orWhere('slug', $request->category);
            });
        }
        
        // Filter berdasarkan harga
        if ($request->has('price_range')) {
            switch($request->price_range) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'under_50k':
                    $query->whereBetween('price', [1, 50000]);
                    break;
                case '50k_100k':
                    $query->whereBetween('price', [50000, 100000]);
                    break;
                case 'over_100k':
                    $query->where('price', '>', 100000);
                    break;
            }
        }
        
        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popular':
                // Cek dulu apakah kolom views ada
                if (Schema::hasColumn('templates', 'views')) {
                    $query->orderBy('views', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
            default:
                $query->latest();
                break;
        }

        $templates = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        // Cek template yang sudah dimiliki user (jika login)
        $userTemplates = [];
        if (auth()->check()) {
            $userTemplates = UserTemplate::where('user_id', auth()->id())
                ->pluck('template_id')
                ->toArray();
        }

        // Get active plan filter info
        $activePlan = null;
        if ($request->has('plan')) {
            $activePlan = PricingPlan::where('slug', $request->plan)->first();
        }

        return view('templates.browse', compact(
            'templates', 
            'categories', 
            'userTemplates',
            'activePlan'
        ));
    }

    public function show($identifier)
{
    try {
        // Cari template berdasarkan slug atau ID
        if (is_numeric($identifier)) {
            // Jika identifier adalah angka, cari berdasarkan ID
            $template = Template::with('category')
                ->where('is_active', true)
                ->findOrFail($identifier);
        } else {
            // Jika bukan angka, cari berdasarkan slug
            $template = Template::with('category')
                ->where('is_active', true)
                ->where('slug', $identifier)
                ->firstOrFail();
        }

        // Increment views
        $this->incrementTemplateViews($template);

        // Cek apakah user sudah memiliki template ini
        $userHasTemplate = false;
        $userTemplate = null;
        
        if (auth()->check()) {
            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $template->id)
                ->first();
            $userHasTemplate = (bool) $userTemplate;
        }

        // Get related templates
        $relatedTemplates = Template::with('category')
            ->where('is_active', true)
            ->where('category_id', $template->category_id)
            ->where('id', '!=', $template->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('templates.show', compact(
            'template', 
            'userHasTemplate', 
            'userTemplate',
            'relatedTemplates'
        ));

    } catch (ModelNotFoundException $e) {
        abort(404, 'Template tidak ditemukan');
    } catch (\Exception $e) {
        \Log::error('Error showing template: ' . $e->getMessage());
        return redirect()->route('templates.index')
            ->with('error', 'Terjadi kesalahan saat memuat template.');
    }
}

    /**
     * Method untuk increment views dengan error handling
     */
    private function incrementTemplateViews(Template $template)
    {
        try {
            if (Schema::hasColumn('templates', 'views')) {
                $template->increment('views');
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal increment views: ' . $e->getMessage());
        }
    }

    public function useTemplate(Template $template)
{
    if (!$template->is_active) {
        abort(404);
    }

    $user = auth()->user();
    
    // Cek apakah user sudah memiliki template
    $userTemplate = UserTemplate::where('user_id', $user->id)
        ->where('template_id', $template->id)
        ->first();
    
    // Jika belum punya dan template berbayar, redirect ke checkout
    if (!$userTemplate && $template->price > 0) {
        return redirect()->route('payment.checkout', $template->id)
            ->with('info', 'Silakan membeli template terlebih dahulu.');
    }
    
    try {
        // Jika belum ada user template, buat baru
        if (!$userTemplate) {
            // Buat user template dengan data dari file
            $userTemplate = $this->createUserTemplateFromFile($user, $template);
        }
        
        // Aktifkan template
        $userTemplate->activate();
        
        // Redirect ke editor
        return redirect()->route('editor.index', ['template' => $template->id])
            ->with('success', 'Template siap untuk diedit!');

    } catch (\Exception $e) {
        \Log::error('Error using template: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat memproses template: ' . $e->getMessage());
    }
}

/**
 * Create UserTemplate from template file
 */
private function createUserTemplateFromFile($user, $template)
{
    // Baca konten dari database atau file
    $htmlContent = $template->html_content;
    
    // Jika ada file HTML di storage, baca dari file
    if ($template->html_file_path && Storage::exists($template->html_file_path)) {
        try {
            $htmlContent = Storage::get($template->html_file_path);
        } catch (\Exception $e) {
            \Log::error('Failed to read HTML file: ' . $e->getMessage());
        }
    }
    
    // Konversi relative paths ke absolute untuk editor
    $processedHtml = $this->processHtmlForEditor($htmlContent, $template);
    
    // Dapatkan CSS dan JS content
    $cssContent = $this->getTemplateCss($template);
    $jsContent = $this->getTemplateJs($template);
    $images = $this->getTemplateImages($template);
    
    // Buat user template - PERBAIKI: gunakan customizations bukan custom_data
    return UserTemplate::create([
        'user_id' => $user->id,
        'template_id' => $template->id,
        'custom_name' => $template->name . ' - Copy',
        'customizations' => json_encode([
            'html' => $processedHtml,
            'css' => $cssContent,
            'js' => $jsContent,
            'images' => $images,
            'settings' => [
                'theme' => 'default',
                'layout' => 'standard',
                'colors' => [],
                'fonts' => []
            ],
            'version' => '1.0',
            'created_at' => now()->toISOString(),
            'last_modified' => now()->toISOString()
        ]),
        'is_active' => true,
        'activated_at' => now()
    ]);
}

/**
 * Create new UserTemplate - Method untuk membuat dari string HTML
 */
public function createUserTemplate($user, $template, $htmlContent = null)
{
    // Jika tidak ada HTML content, gunakan dari template
    if (!$htmlContent) {
        $htmlContent = $template->html_content ?? '';
    }
    
    $cssContent = $this->getTemplateCss($template);
    $jsContent = $this->getTemplateJs($template);
    $images = $this->getTemplateImages($template);
    
    return UserTemplate::create([
        'user_id' => $user->id,
        'template_id' => $template->id,
        'custom_name' => $template->name . ' - ' . $user->name,
        'customizations' => json_encode([
            'html' => $htmlContent,
            'css' => $cssContent,
            'js' => $jsContent,
            'images' => $images,
            'settings' => [
                'theme' => 'default',
                'layout' => 'standard',
                'colors' => [],
                'fonts' => []
            ],
            'version' => '1.0',
            'created_at' => now()->toISOString(),
            'last_modified' => now()->toISOString()
        ]),
        'is_active' => false
    ]);
}
    
    /**
     * Process HTML untuk editor (konversi relative paths)
     */
    private function processHtmlForEditor($html, $template)
    {
        // Dapatkan base path dari html file
        $basePath = '';
        if ($template->html_file_path) {
            $basePath = dirname($template->html_file_path);
        }
        
        if (empty($basePath)) {
            return $html;
        }
        
        // Konversi semua relative paths ke storage URLs
        $patterns = [
            '/src="(?!https?:\/\/)([^"]+)"/',
            '/href="(?!https?:\/\/)([^"]+\.(?:css|png|jpg|jpeg|gif|svg|webp))"/',
            '/url\(["\']?(?!https?:\/\/)([^"\'\)]+)["\']?\)/'
        ];
        
        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function($matches) use ($basePath, $template) {
                $relativePath = $matches[1];
                
                // Coba beberapa kemungkinan path
                $possiblePaths = [
                    $basePath . '/' . $relativePath,
                    'templates/' . basename($basePath) . '/' . $relativePath,
                    dirname($basePath) . '/' . $relativePath,
                ];
                
                foreach ($possiblePaths as $path) {
                    if (Storage::exists($path)) {
                        return str_replace($relativePath, Storage::url($path), $matches[0]);
                    }
                }
                
                // Jika tidak ditemukan, return asli
                return $matches[0];
            }, $html);
        }
        
        return $html;
    }
    
    /**
     * Preview template
     */
    public function preview($id)
    {
        try {
            $template = Template::findOrFail($id);
            
            // Baca konten HTML dari database atau file
            $htmlContent = $template->html_content;
            
            // Jika tidak ada di database, baca dari file
            if (!$htmlContent && $template->html_file_path) {
                if (Storage::exists($template->html_file_path)) {
                    try {
                        $htmlContent = Storage::get($template->html_file_path);
                        
                        // Proses HTML untuk preview
                        $htmlContent = $this->processHtmlForPreview($htmlContent, $template);
                    } catch (\Exception $e) {
                        \Log::error('Failed to read HTML file: ' . $e->getMessage());
                    }
                }
            }
            
            return view('templates.preview', compact('template', 'htmlContent'));

        } catch (\Exception $e) {
            \Log::error('Error previewing template: ' . $e->getMessage());
            return redirect()->route('templates.index')
                ->with('error', 'Terjadi kesalahan saat memuat preview template.');
        }
    }
    
    /**
     * Process HTML untuk preview
     */
    private function processHtmlForPreview($html, $template)
    {
        // Dapatkan base path dari html file
        $basePath = '';
        if ($template->html_file_path) {
            $basePath = dirname($template->html_file_path);
        }
        
        if (empty($basePath)) {
            return $html;
        }
        
        // Patterns untuk mencari src, href, dan url()
        $patterns = [
            '/src=["\']([^"\']+)["\']/i',
            '/href=["\']([^"\']+\.(?:css|png|jpg|jpeg|gif|svg|webp|ico))["\']/i',
            '/url\(["\']?([^"\'\)]+)["\']?\)/i'
        ];
        
        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function($matches) use ($basePath, $template) {
                $fullMatch = $matches[0];
                $relativePath = $matches[1];
                
                // Skip jika sudah absolute URL
                if (strpos($relativePath, 'http://') === 0 || 
                    strpos($relativePath, 'https://') === 0 ||
                    strpos($relativePath, '//') === 0 ||
                    strpos($relativePath, 'data:') === 0) {
                    return $fullMatch;
                }
                
                // Skip jika ini anchor link
                if (strpos($relativePath, '#') === 0) {
                    return $fullMatch;
                }
                
                // Bersihkan path
                $relativePath = ltrim($relativePath, './');
                
                // Coba beberapa kemungkinan path
                $possiblePaths = [
                    // Path relatif dari file HTML
                    $basePath . '/' . $relativePath,
                    // Path di assets folder
                    $template->images_path . '/' . basename($relativePath),
                    // Path di root templates
                    dirname($basePath) . '/' . $relativePath,
                    // Path di direktori yang sama dengan file HTML
                    dirname($template->html_file_path) . '/' . $relativePath,
                ];
                
                foreach ($possiblePaths as $path) {
                    if (Storage::exists($path)) {
                        $url = Storage::url($path);
                        
                        if (str_contains($fullMatch, 'url(')) {
                            return 'url("' . $url . '")';
                        }
                        return str_replace($relativePath, $url, $fullMatch);
                    }
                }
                
                return $fullMatch;
            }, $html);
        }
        
        return $html;
    }
    
    /**
     * Get CSS content dari template
     */
    private function getTemplateCss($template)
    {
        $cssContent = '';
        
        // Jika ada CSS file di storage
        if ($template->css_file_path) {
            // Cek apakah ini path file atau folder
            if (str_ends_with($template->css_file_path, '/')) {
                // Ini folder, baca semua file CSS
                $cssDir = $template->css_file_path;
                if (Storage::exists($cssDir)) {
                    $files = Storage::files($cssDir);
                    foreach ($files as $file) {
                        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'css') {
                            try {
                                $cssContent .= Storage::get($file) . "\n";
                            } catch (\Exception $e) {
                                \Log::error('Failed to read CSS file: ' . $file);
                            }
                        }
                    }
                }
            } else {
                // Ini file tunggal
                if (Storage::exists($template->css_file_path)) {
                    try {
                        $cssContent = Storage::get($template->css_file_path);
                    } catch (\Exception $e) {
                        \Log::error('Failed to read CSS file: ' . $template->css_file_path);
                    }
                }
            }
        }
        
        return $cssContent;
    }
    
    /**
     * Get JS content dari template
     */
    private function getTemplateJs($template)
    {
        $jsContent = '';
        
        // Jika ada JS file di storage
        if ($template->js_file_path) {
            // Cek apakah ini path file atau folder
            if (str_ends_with($template->js_file_path, '/')) {
                // Ini folder, baca semua file JS
                $jsDir = $template->js_file_path;
                if (Storage::exists($jsDir)) {
                    $files = Storage::files($jsDir);
                    foreach ($files as $file) {
                        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'js') {
                            try {
                                $jsContent .= Storage::get($file) . "\n";
                            } catch (\Exception $e) {
                                \Log::error('Failed to read JS file: ' . $file);
                            }
                        }
                    }
                }
            } else {
                // Ini file tunggal
                if (Storage::exists($template->js_file_path)) {
                    try {
                        $jsContent = Storage::get($template->js_file_path);
                    } catch (\Exception $e) {
                        \Log::error('Failed to read JS file: ' . $template->js_file_path);
                    }
                }
            }
        }
        
        return $jsContent;
    }
    
    /**
     * Get images dari template
     */
    private function getTemplateImages($template)
    {
        $images = [];
        
        if ($template->images_path && Storage::exists($template->images_path)) {
            $files = Storage::files($template->images_path);
            
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = [
                        'path' => $file,
                        'url' => Storage::url($file),
                        'filename' => basename($file)
                    ];
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Tentukan range harga template berdasarkan plan yang dipilih
     */
    private function getPriceRangeForPlan($planSlug)
    {
        $priceRanges = [
            'free' => ['min' => 0, 'max' => 0],
            'starter' => ['min' => 0, 'max' => 50000],
            'professional' => ['min' => 50000, 'max' => 200000],
            'enterprise' => ['min' => 200000, 'max' => 1000000],
        ];
        
        return $priceRanges[$planSlug] ?? null;
    }

    /**
     * API untuk mendapatkan templates berdasarkan filter
     */
    public function getTemplatesByFilter(Request $request)
    {
        try {
            $query = Template::with('category')->where('is_active', true);
            
            // Apply filters
            if ($request->has('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
            }
            
            if ($request->has('price_range')) {
                switch($request->price_range) {
                    case 'free':
                        $query->where('price', 0);
                        break;
                    case 'under_50k':
                        $query->whereBetween('price', [1, 50000]);
                        break;
                    case '50k_100k':
                        $query->whereBetween('price', [50000, 100000]);
                        break;
                    case 'over_100k':
                        $query->where('price', '>', 100000);
                        break;
                }
            }
            
            $templates = $query->latest()->get();
            
            return response()->json([
                'success' => true,
                'templates' => $templates,
                'count' => $templates->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat templates.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Purchase template (untuk tombol Beli Sekarang)
     */
    public function purchase(Request $request, $id)
    {
        try {
            $template = Template::findOrFail($id);
            
            // Pastikan user sudah login
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('redirect_url', route('templates.show', $id))
                    ->with('info', 'Silakan login terlebih dahulu untuk membeli template.');
            }

            $user = auth()->user();

            // Cek apakah user sudah memiliki template ini
            if ($user->hasTemplate($template->id)) {
                return redirect()->route('templates.show', $template->id)
                    ->with('info', 'Anda sudah memiliki template ini.');
            }

            // Redirect ke halaman checkout
            return redirect()->route('payment.checkout', $template->id);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Template tidak ditemukan');
        } catch (\Exception $e) {
            \Log::error('Error purchasing template: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pembelian.');
        }
    }
}