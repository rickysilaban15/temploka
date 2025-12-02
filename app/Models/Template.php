<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'thumbnail',
        'preview_images',
        'features',
        'category_id',
        'is_featured',
        'is_active',
        'views',
        'html_file_path',
        'css_file_path',
        'js_file_path',
        'images_path',
        'html_content' // tambahkan ini jika Anda akan menambahkan kolom
    ];

    protected $casts = [
        'preview_images' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Accessor untuk memastikan features selalu array
    public function getFeaturesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($value) ? $value : [];
    }

    // Accessor untuk preview_images
    public function getPreviewImagesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($value) ? $value : [];
    }

    /**
     * Get HTML content from file
     * Jika tidak ada kolom html_content di database, ambil dari file
     */
    public function getHtmlContentAttribute()
    {
        // Coba ambil dari kolom database jika ada
        if (isset($this->attributes['html_content']) && !empty($this->attributes['html_content'])) {
            return $this->attributes['html_content'];
        }
        
        // Fallback: ambil dari file storage
        if ($this->html_file_path && Storage::exists($this->html_file_path)) {
            return Storage::get($this->html_file_path);
        }
        
        return '<p>Template content not found</p>';
    }

    /**
     * Set HTML content attribute
     */
    public function setHtmlContentAttribute($value)
    {
        $this->attributes['html_content'] = $value;
    }

    /**
     * Get CSS content from file
     */
    public function getCssContentAttribute()
    {
        if ($this->css_file_path && Storage::exists($this->css_file_path)) {
            return Storage::get($this->css_file_path);
        }
        return '';
    }

    /**
     * Get JS content from file
     */
    public function getJsContentAttribute()
    {
        if ($this->js_file_path && Storage::exists($this->js_file_path)) {
            return Storage::get($this->js_file_path);
        }
        return '';
    }

    /**
     * Get images from template folder
     */
    public function getTemplateImagesAttribute()
    {
        $images = [];
        
        // Coba dari images_path
        if ($this->images_path && Storage::exists($this->images_path)) {
            $files = Storage::files($this->images_path);
            
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = [
                        'path' => $file,
                        'url' => Storage::url($file)
                    ];
                }
            }
        }
        
        // Jika tidak ada di images_path, cari di seluruh folder template
        if (empty($images) && $this->html_file_path) {
            $templateDir = dirname($this->html_file_path);
            
            // Cari semua file gambar di folder template
            $allFiles = Storage::allFiles($templateDir);
            
            foreach ($allFiles as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = [
                        'path' => $file,
                        'url' => Storage::url($file)
                    ];
                }
            }
        }
        
        return $images;
    }

    /**
     * Get the full path to template directory
     */
    public function getTemplateDirectoryAttribute()
    {
        if ($this->html_file_path) {
            return dirname($this->html_file_path);
        }
        return null;
    }

    /**
     * Check if template has file in storage
     */
    public function hasFileInStorage()
    {
        return $this->html_file_path && Storage::exists($this->html_file_path);
    }

    /**
     * Get template asset URL
     */
    public function getAssetUrl($relativePath)
    {
        $templateDir = $this->template_directory;
        
        if (!$templateDir) {
            return null;
        }
        
        $fullPath = $templateDir . '/' . ltrim($relativePath, './');
        
        if (Storage::exists($fullPath)) {
            return Storage::url($fullPath);
        }
        
        return null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function userTemplates()
    {
        return $this->hasMany(UserTemplate::class);
    }
}