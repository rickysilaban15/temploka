<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\UserTemplate;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    public function index($template = null)
    {
        try {
            $currentTemplate = null;
            $userTemplate = null;
            $userHasPaid = false;
            $templateContent = null;

            if ($template) {
                $currentTemplate = Template::find($template);

                if ($currentTemplate) {
                    // Cek apakah user memiliki data template
                    $userTemplate = UserTemplate::where('user_id', auth()->id())
                        ->where('template_id', $currentTemplate->id)
                        ->first();
                    
                    // Ambil konten template (dari file atau database)
                    $templateContent = $this->getTemplateContentForEditor($currentTemplate);
                        
                    // Jika belum ada user template, buat dari template file
                    if (!$userTemplate) {
                        $userTemplate = $this->createUserTemplateFromFile($currentTemplate, $templateContent);
                    }

                    // Cek apakah user harus bayar
                    if ($currentTemplate->price > 0) {
                        $paidOrder = Order::where('user_id', auth()->id())
                            ->where('template_id', $currentTemplate->id)
                            ->where('status', 'paid')
                            ->first();
                        $userHasPaid = (bool) $paidOrder;
                    } else {
                        $userHasPaid = true;
                    }
                }
            }

            $userTemplates = UserTemplate::with('template')
                ->where('user_id', auth()->id())
                ->get();

            return view('editor.index', compact(
                'currentTemplate', 
                'userTemplate', 
                'userTemplates', 
                'userHasPaid',
                'templateContent'
            ));

        } catch (\Exception $e) {
            Log::error('EditorController index error: ' . $e->getMessage());
            return redirect()->route('dashboard.templates')
                ->with('error', 'Terjadi kesalahan saat memuat editor.');
        }
    }

    /**
     * Preview template
     */
    public function preview($templateId)
    {
        try {
            $userTemplate = UserTemplate::with('template')
                ->where('user_id', auth()->id())
                ->where('template_id', $templateId)
                ->firstOrFail();

            return view('editor.preview', compact('userTemplate'));

        } catch (\Exception $e) {
            Log::error('EditorController preview error: ' . $e->getMessage());
            return redirect()->route('editor.index', $templateId)
                ->with('error', 'Template tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * Save template data
     */
    public function save(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:templates,id',
                'custom_data' => 'required|string'
            ]);

            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $request->template_id)
                ->first();

            if (!$userTemplate) {
                $userTemplate = new UserTemplate([
                    'user_id' => auth()->id(),
                    'template_id' => $request->template_id,
                    'custom_name' => 'Edited Template',
                    'customizations' => [] // PERBAIKAN: gunakan customizations bukan custom_data
                ]);
            }

            // Parse customizations data (PERBAIKAN: gunakan customizations)
            $customizations = $userTemplate->customizations;
            $customizations['html'] = $request->custom_data;
            $customizations['updated_at'] = now()->toISOString();
            $customizations['last_modified'] = now()->toISOString();

            $userTemplate->customizations = $customizations; // PERBAIKAN
            $userTemplate->save();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan template.'
            ], 500);
        }
    }

    /**
     * Publish template
     */
    public function publish(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:templates,id'
            ]);

            $template = Template::findOrFail($request->template_id);
            
            // Cek jika template berbayar dan user belum membayar
            if ($template->price > 0) {
                $paidOrder = Order::where('user_id', auth()->id())
                    ->where('template_id', $template->id)
                    ->where('status', 'paid')
                    ->first();

                if (!$paidOrder) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Template ini berbayar. Silakan checkout terlebih dahulu.',
                        'redirect_url' => route('payment.checkout', $template)
                    ]);
                }
            }

            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $template->id)
                ->firstOrFail();

            $userTemplate->is_active = true;
            $userTemplate->activated_at = now(); // PERBAIKAN: gunakan activated_at bukan published_at
            $userTemplate->save();

            // Generate published URL
            $publishedUrl = route('templates.published', [
                'user' => auth()->user()->username ?? auth()->id(),
                'template' => $userTemplate->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil dipublish!',
                'published_url' => $publishedUrl,
                'redirect_url' => route('dashboard.templates')
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController publish error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mempublish template.'
            ], 500);
        }
    }

    /**
     * Reset template to original
     */
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:templates,id'
            ]);

            $template = Template::findOrFail($request->template_id);
            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $template->id)
                ->first();

            if ($userTemplate) {
                // Reset to original template content
                $templateContent = $this->getTemplateContentForEditor($template);
                
                $customizations = [
                    'html' => $templateContent,
                    'css' => $this->getCssContent($template),
                    'js' => $this->getJsContent($template),
                    'images' => $this->getTemplateImages($template),
                    'settings' => [
                        'theme' => 'default',
                        'layout' => 'standard'
                    ],
                    'reset_at' => now()->toISOString(),
                    'last_modified' => now()->toISOString()
                ];

                $userTemplate->customizations = $customizations; // PERBAIKAN
                $userTemplate->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil direset ke keadaan semula.'
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController reset error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset template.'
            ], 500);
        }
    }

    /**
     * Upload image
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120'
            ]);

            $user = auth()->user();
            $path = $request->file('image')->store(
                'uploads/editor/' . $user->id,
                'public'
            );

            return response()->json([
                'success' => true,
                'url' => Storage::url($path),
                'path' => $path
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController uploadImage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar.'
            ], 500);
        }
    }

    /**
     * Get template content for editor
     */
    public function getTemplateContentApi(Request $request, $templateId)
    {
        try {
            $template = Template::findOrFail($templateId);
            
            $content = [
                'html' => $this->getTemplateContentForEditor($template),
                'css' => $this->getCssContent($template),
                'js' => $this->getJsContent($template),
                'images' => $this->getTemplateImages($template),
            ];

            return response()->json([
                'success' => true,
                'content' => $content
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController getTemplateContent error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get template content'
            ], 500);
        }
    }

    /**
     * Get template content untuk editor
     */
    private function getTemplateContentForEditor($template)
    {
        $htmlContent = $template->html_content;
        
        // Jika ada file HTML di storage
        if ($template->html_file_path && Storage::exists($template->html_file_path)) {
            try {
                $htmlContent = Storage::get($template->html_file_path);
            } catch (\Exception $e) {
                Log::error('Failed to read HTML file: ' . $e->getMessage());
            }
        }
        
        // Proses HTML untuk editor
        if ($htmlContent) {
            return $this->processHtmlForEditor($htmlContent, $template);
        }
        
        // Fallback content
        return '<div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">' . $template->name . '</h1>
            <p class="text-gray-600">Template content will appear here.</p>
        </div>';
    }
    
    /**
     * Create user template from template file
     */
    private function createUserTemplateFromFile($template, $htmlContent = null)
    {
        $user = auth()->user();
        
        // Gunakan content yang sudah diproses
        if (!$htmlContent) {
            $htmlContent = $this->getTemplateContentForEditor($template);
        }
        
        // Dapatkan CSS dan JS content
        $cssContent = $this->getCssContent($template);
        $jsContent = $this->getJsContent($template);
        $images = $this->getTemplateImages($template);
        
        // Buat user template - PERBAIKAN: gunakan customizations
        return UserTemplate::create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'custom_name' => $template->name . ' - My Copy',
            'customizations' => [
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
            ],
            'is_active' => false,
        ]);
    }
    
    /**
     * Process HTML untuk editor
     */
    private function processHtmlForEditor($html, $template)
    {
        // Konversi relative paths ke absolute
        $html = $this->convertRelativePaths($html, $template);
        
        // Tambahkan atribut untuk editor
        $html = $this->addEditorAttributes($html);
        
        return $html;
    }
    
    /**
     * Get CSS content
     */
    private function getCssContent($template)
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
                                Log::error('Failed to read CSS file: ' . $file);
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
                        Log::error('Failed to read CSS file: ' . $template->css_file_path);
                    }
                }
            }
        }
        
        return $cssContent;
    }
    
    /**
     * Get JS content
     */
    private function getJsContent($template)
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
                                Log::error('Failed to read JS file: ' . $file);
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
                        Log::error('Failed to read JS file: ' . $template->js_file_path);
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
     * Konversi relative paths ke absolute untuk assets
     */
    private function convertRelativePaths($html, $template)
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
                
                // Jika tidak ditemukan, coba konstruksi URL dari path asli
                $constructedPath = 'templates/' . basename(dirname($basePath)) . '/' . basename($basePath) . '/' . $relativePath;
                if (Storage::exists($constructedPath)) {
                    $url = Storage::url($constructedPath);
                    
                    if (str_contains($fullMatch, 'url(')) {
                        return 'url("' . $url . '")';
                    }
                    return str_replace($relativePath, $url, $fullMatch);
                }
                
                return $fullMatch;
            }, $html);
        }
        
        return $html;
    }
    
    /**
     * Tambahkan data attributes untuk editor
     */
    private function addEditorAttributes($html)
    {
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            
            $xpath = new \DOMXPath($dom);
            
            // Elements yang bisa diedit
            $elements = $xpath->query('//h1|//h2|//h3|//h4|//h5|//p|//span|//a|//li|//div[contains(@class, "content")]|//div[contains(@class, "text")]');
            
            foreach ($elements as $element) {
                $currentClass = $element->getAttribute('class');
                $element->setAttribute('class', trim($currentClass . ' editable'));
                $element->setAttribute('data-editable', 'true');
            }
            
            return $dom->saveHTML();
        } catch (\Exception $e) {
            Log::error('Error adding editor attributes: ' . $e->getMessage());
            return $html; // Return original jika error
        }
    }

    /**
     * Get user's published templates
     */
    public function getPublishedTemplates()
    {
        $userTemplates = UserTemplate::with('template')
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $userTemplates
        ]);
    }

    /**
     * View published template
     */
    public function viewPublished($userId, $templateId)
    {
        try {
            $userTemplate = UserTemplate::with(['template', 'user'])
                ->where('id', $templateId)
                ->where('is_active', true)
                ->firstOrFail();

            // Increment views (tambah field views jika belum ada)
            if (Schema::hasColumn('user_templates', 'views')) {
                $userTemplate->increment('views');
            }

            return view('editor.published', compact('userTemplate'));

        } catch (\Exception $e) {
            abort(404, 'Template tidak ditemukan atau belum dipublish.');
        }
    }

    /**
     * Duplicate template
     */
    public function duplicate(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:templates,id'
            ]);

            $originalTemplate = Template::findOrFail($request->template_id);
            
            // Get current user template
            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $originalTemplate->id)
                ->first();

            // Create new user template record with customizations
            $newUserTemplate = new UserTemplate();
            $newUserTemplate->user_id = auth()->id();
            $newUserTemplate->template_id = $originalTemplate->id;
            $newUserTemplate->custom_name = $originalTemplate->name . ' (Copy)';
            
            // Get customizations or create from template
            if ($userTemplate) {
                $customizations = $userTemplate->customizations;
                $customizations['duplicated_from'] = $originalTemplate->id;
                $customizations['duplicated_at'] = now()->toISOString();
            } else {
                $customizations = [
                    'html' => $this->getTemplateContentForEditor($originalTemplate),
                    'css' => $this->getCssContent($originalTemplate),
                    'js' => $this->getJsContent($originalTemplate),
                    'images' => $this->getTemplateImages($originalTemplate),
                    'settings' => [
                        'theme' => 'default',
                        'layout' => 'standard'
                    ],
                    'duplicated_from' => $originalTemplate->id,
                    'created_at' => now()->toISOString(),
                ];
            }
            
            $newUserTemplate->customizations = $customizations;
            $newUserTemplate->is_active = false;
            $newUserTemplate->save();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil diduplikasi!',
                'new_template_id' => $newUserTemplate->id,
                'redirect_url' => route('editor.index', ['template' => $originalTemplate->id, 'user_template' => $newUserTemplate->id])
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController duplicate error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menduplikasi template.'
            ], 500);
        }
    }

    /**
     * Delete template
     */
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'template_id' => 'required|exists:templates,id'
            ]);

            $userTemplate = UserTemplate::where('user_id', auth()->id())
                ->where('template_id', $request->template_id)
                ->first();

            if ($userTemplate) {
                $userTemplate->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil dihapus!',
                'redirect_url' => route('dashboard.templates')
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus template.'
            ], 500);
        }
    }

    /**
     * Get HTML content untuk debug (opsional)
     */
    public function debugTemplate($id)
    {
        try {
            $template = Template::findOrFail($id);
            $htmlContent = $this->getTemplateContentForEditor($template);
            
            return response($htmlContent)->header('Content-Type', 'text/html');
            
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage());
        }
    }

    /**
     * Get template content from user template
     */
    public function getTemplateData(Request $request, $templateId)
    {
        try {
            $userTemplate = UserTemplate::with('template')
                ->where('user_id', auth()->id())
                ->where('template_id', $templateId)
                ->firstOrFail();

            $htmlContent = $userTemplate->getHtmlContent();
            $cssContent = $userTemplate->getCssContent();
            $jsContent = $userTemplate->getJsContent();

            return response()->json([
                'success' => true,
                'html' => $htmlContent,
                'css' => $cssContent,
                'js' => $jsContent,
                'template' => $userTemplate->template
            ]);

        } catch (\Exception $e) {
            Log::error('EditorController getTemplateData error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get template data'
            ], 500);
        }
    }
}