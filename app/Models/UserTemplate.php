<?php
// app/Models/UserTemplate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'custom_name', // Tambahkan ini
        'customizations', // Ubah dari custom_data ke customizations
        'is_active',
        'activated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'customizations' => 'array' // Ubah dari custom_data ke customizations
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    // Accessor untuk customizations
    public function getCustomizationsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        try {
            return json_decode($value, true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    // Mutator untuk customizations
    public function setCustomizationsAttribute($value)
    {
        $this->attributes['customizations'] = is_string($value) ? $value : json_encode($value);
    }

    // Get HTML content dari customizations
    public function getHtmlContent()
    {
        $customizations = $this->customizations;
        return $customizations['html'] ?? '';
    }

    // Update HTML content
    public function updateHtmlContent($html)
    {
        $customizations = $this->customizations;
        $customizations['html'] = $html;
        $customizations['last_modified'] = now()->toISOString();
        $this->customizations = $customizations;
        $this->save();
    }

    // Get CSS content dari customizations
    public function getCssContent()
    {
        $customizations = $this->customizations;
        return $customizations['css'] ?? '';
    }

    // Get JS content dari customizations
    public function getJsContent()
    {
        $customizations = $this->customizations;
        return $customizations['js'] ?? '';
    }

    // Scope untuk template aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk template user tertentu
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk mengaktifkan template
    public function activate()
    {
        // Nonaktifkan template lain user ini
        UserTemplate::where('user_id', $this->user_id)
                   ->where('id', '!=', $this->id)
                   ->update(['is_active' => false]);
        
        // Aktifkan template ini
        $this->update([
            'is_active' => true,
            'activated_at' => now()
        ]);
        
        return $this;
    }
}