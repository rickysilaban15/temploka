<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        // FREE TEMPLATES - HANYA Basic Portfolio YANG PAKAI FILE
        $freeTemplates = [
            [
                'name' => 'Basic Portfolio',
                'slug' => 'basic-portfolio',
                'description' => 'Template portfolio gratis untuk memulai karir kreatif Anda',
                'price' => 0,
                'thumbnail' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=500&h=300&fit=crop',
                'preview_images' => json_encode([
                    'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1522542550221-31fd19575a2d?w=800&h=600&fit=crop'
                ]),
                'features' => json_encode(['Project Gallery', 'About Section', 'Contact Form', 'Mobile Friendly', 'Responsive Design']),
                'category_id' => $categories->where('slug', 'portfolio')->first()->id,
                'is_featured' => true,
                'is_active' => true,
                // Path file untuk portfolio free (dari storage)
                'html_file_path' => 'templates/free/portfolio/index.html',
                'css_file_path' => 'templates/free/portfolio/css/styles.css',
                'js_file_path' => 'templates/free/portfolio/js/scripts.js',
                'images_path' => 'templates/free/portfolio/assets',
                'html_content' => $this->getHtmlContent('free/portfolio/index.html'),
            ],
            // ... template free lainnya tanpa css_content dan js_content
        ];

        foreach ($freeTemplates as $templateData) {
            Template::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }

        // PREMIUM TEMPLATES - HANYA E-Commerce Store YANG PAKAI FILE
        $premiumTemplates = [
            [
                'name' => 'E-Commerce Store',
                'slug' => 'e-commerce-store',
                'description' => 'Template toko online lengkap dengan sistem keranjang belanja dan checkout',
                'price' => 399000,
                'thumbnail' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&h=300&fit=crop',
                'preview_images' => json_encode([
                    'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&h=600&fit=crop'
                ]),
                'features' => json_encode(['Product Catalog', 'Shopping Cart', 'Payment Integration', 'Order Tracking', 'Customer Reviews', 'Inventory Management']),
                'category_id' => $categories->where('slug', 'e-commerce')->first()->id,
                'is_featured' => true,
                'is_active' => true,
                // Path untuk template premium (dari storage)
                'html_file_path' => 'templates/premium/startbootstrap-agency-gh-pages/index.html',
                'css_file_path' => 'templates/premium/startbootstrap-agency-gh-pages/css/styles.css',
                'js_file_path' => 'templates/premium/startbootstrap-agency-gh-pages/js/scripts.js',
                'images_path' => 'templates/premium/startbootstrap-agency-gh-pages/assets/img',
                'html_content' => $this->getHtmlContent('premium/startbootstrap-agency-gh-pages/index.html'),
            ],
            // ... template premium lainnya tanpa css_content dan js_content
        ];

        foreach ($premiumTemplates as $templateData) {
            Template::updateOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }

        // ... sisa kode tetap sama
    }

    /**
     * Helper untuk membaca konten HTML dari file di storage
     */
    private function getHtmlContent($filePath)
    {
        if (Storage::exists($filePath)) {
            try {
                $content = Storage::get($filePath);
                
                // Bersihkan konten HTML
                $content = $this->cleanHtmlContent($content);
                
                // Potong jika terlalu panjang
                if (strlen($content) > 65000) {
                    $content = substr($content, 0, 65000);
                }
                
                return $content;
            } catch (\Exception $e) {
                \Log::warning("Failed to read HTML file: {$filePath} - " . $e->getMessage());
            }
        }
        
        return $this->getBasicHtml(basename(dirname($filePath)));
    }


    /**
     * Get CSS content from folder
     */
    private function getCssContent($templatePath)
    {
        $cssContent = '';
        
        // Try to get the main CSS file first
        $mainCssPath = "templates/{$templatePath}/css/styles.css";
        if (Storage::exists($mainCssPath)) {
            try {
                $cssContent = Storage::get($mainCssPath);
            } catch (\Exception $e) {
                \Log::warning("Failed to read CSS file: {$mainCssPath}");
            }
        }
        
        // If main CSS doesn't exist, try to get all CSS files
        if (empty($cssContent)) {
            $cssDir = "templates/{$templatePath}/css/";
            if (Storage::exists($cssDir)) {
                $files = Storage::files($cssDir);
                
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                        try {
                            $cssContent .= Storage::get($file) . "\n";
                        } catch (\Exception $e) {
                            \Log::warning("Failed to read CSS file: {$file}");
                        }
                    }
                }
            }
        }
        
        return $cssContent;
    }

    /**
     * Get JS content from folder
     */
    private function getJsContent($templatePath)
    {
        $jsContent = '';
        
        // Try to get the main JS file first
        $mainJsPath = "templates/{$templatePath}/js/scripts.js";
        if (Storage::exists($mainJsPath)) {
            try {
                $jsContent = Storage::get($mainJsPath);
            } catch (\Exception $e) {
                \Log::warning("Failed to read JS file: {$mainJsPath}");
            }
        }
        
        // If main JS doesn't exist, try to get all JS files
        if (empty($jsContent)) {
            $jsDir = "templates/{$templatePath}/js/";
            if (Storage::exists($jsDir)) {
                $files = Storage::files($jsDir);
                
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                        try {
                            $jsContent .= Storage::get($file) . "\n";
                        } catch (\Exception $e) {
                            \Log::warning("Failed to read JS file: {$file}");
                        }
                    }
                }
            }
        }
        
        return $jsContent;
    }

    /**
     * Clean HTML content
     */
    private function cleanHtmlContent($content)
    {
        // Remove HTML comments
        $content = preg_replace('/<!--(.*?)-->/s', '', $content);
        
        // Trim whitespace
        $content = trim($content);
        
        // Escape untuk disimpan di database
        $content = htmlspecialchars_decode($content, ENT_QUOTES);
        
        return $content;
    }

    /**
     * Generate basic HTML untuk template yang tidak ada file-nya
     */
    private function getBasicHtml($templateName)
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$templateName}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; 
            line-height: 1.6; 
            color: #333;
            background: #f8f9fa;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 20px; 
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 100px 0; 
            text-align: center; 
        }
        .header h1 { 
            font-size: 3.5rem; 
            margin-bottom: 20px; 
            font-weight: 700;
        }
        .header p { 
            font-size: 1.3rem; 
            max-width: 700px; 
            margin: 0 auto 40px; 
            opacity: 0.9;
        }
        .btn { 
            display: inline-block; 
            padding: 15px 40px; 
            background: white; 
            color: #667eea; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: 600;
            font-size: 1.1rem;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .features { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            margin: 80px 0; 
        }
        .feature { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .feature:hover { 
            transform: translateY(-5px);
        }
        .feature h3 { 
            color: #667eea; 
            margin-bottom: 15px; 
            font-size: 1.5rem;
        }
        .feature p { 
            color: #666; 
            line-height: 1.7;
        }
        .footer { 
            background: #2d3748; 
            color: white; 
            padding: 60px 0; 
            text-align: center; 
            margin-top: 80px;
        }
        .section-title {
            text-align: center;
            margin: 60px 0 40px;
            font-size: 2.5rem;
            color: #2d3748;
        }
        @media (max-width: 768px) {
            .header h1 { font-size: 2.5rem; }
            .header p { font-size: 1.1rem; }
            .features { grid-template-columns: 1fr; }
            .btn { padding: 12px 30px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>{$templateName}</h1>
            <p>A beautiful, responsive template for your next project. Easy to customize and built with modern web standards.</p>
            <a href="#features" class="btn">Get Started</a>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title" id="features">Amazing Features</h2>
        <div class="features">
            <div class="feature">
                <h3>Responsive Design</h3>
                <p>Looks great on all devices from mobile to desktop. Built with mobile-first approach.</p>
            </div>
            <div class="feature">
                <h3>Modern Layout</h3>
                <p>Clean, professional design that captures attention and delivers your message effectively.</p>
            </div>
            <div class="feature">
                <h3>Easy Customization</h3>
                <p>Edit content directly in your browser. No coding experience required to get started.</p>
            </div>
        </div>

        <div class="content-section">
            <h2 class="section-title">About This Template</h2>
            <div style="background: white; padding: 50px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                <p style="font-size: 1.2rem; line-height: 1.8; margin-bottom: 25px;">
                    This is a professional template designed to help you create stunning websites quickly and easily. 
                    Whether you're building a portfolio, business website, or online store, this template provides 
                    everything you need to get started.
                </p>
                <p style="font-size: 1.2rem; line-height: 1.8;">
                    Customize colors, fonts, and layout to match your brand. Add your own images and content 
                    to create a unique online presence that represents you or your business perfectly.
                </p>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p style="font-size: 1.1rem; margin-bottom: 20px;">&copy; 2024 {$templateName}. All rights reserved.</p>
            <p style="opacity: 0.8;">Made with ❤️ for web community</p>
        </div>
    </div>

    <script>
        // Simple JavaScript for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Add animation to features on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all features
            document.querySelectorAll('.feature').forEach(feature => {
                feature.style.opacity = '0';
                feature.style.transform = 'translateY(20px)';
                feature.style.transition = 'opacity 0.6s, transform 0.6s';
                observer.observe(feature);
            });
        });
    </script>
</body>
</html>
HTML;
    }

    private function createAdditionalTemplates($categories)
    {
        $featureSets = [
            ['Responsive Design', 'SEO Optimized', 'Fast Loading', 'Easy Customization'],
            ['Mobile First', 'Cross Browser', 'Clean Code', 'Documentation'],
            ['Contact Form', 'Google Maps', 'Social Media', 'Newsletter'],
            ['Gallery System', 'Blog System', 'Portfolio Layout', 'Testimonials'],
            ['E-commerce Ready', 'Payment Gateway', 'Product Management', 'Order System'],
        ];

        for ($i = 1; $i <= 15; $i++) {
            // 30% chance untuk template gratis
            $isFree = rand(1, 10) <= 3;
            
            Template::updateOrCreate(
                ['slug' => "template-{$i}"],
                [
                    'name' => $isFree ? "Free Template {$i}" : "Template Premium {$i}",
                    'slug' => "template-{$i}",
                    'description' => $isFree 
                        ? "Template gratis dengan fitur dasar untuk memulai website Anda." 
                        : "Template website premium dengan fitur lengkap untuk berbagai kebutuhan bisnis.",
                    'price' => $isFree ? 0 : rand(150000, 500000),
                    'thumbnail' => "https://picsum.photos/500/300?random={$i}",
                    'preview_images' => json_encode([
                        "https://picsum.photos/800/600?random={$i}",
                        "https://picsum.photos/800/600?random=" . ($i + 100)
                    ]),
                    'features' => json_encode($featureSets[array_rand($featureSets)]),
                    'category_id' => $categories->random()->id,
                    'is_featured' => $isFree ? false : (rand(0, 1) == 1),
                    'is_active' => true,
                    'html_content' => $this->getBasicHtml($isFree ? "Free Template {$i}" : "Template Premium {$i}"),
                ]
            );
        }
    }
}