<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'avatar',
        'bio',
        'is_admin',
        'onboarding_step',
        'onboarding_completed',
        'business_type',
        'business_goals',
        'enabled_modules'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'onboarding_completed' => 'boolean',
            'business_goals' => 'array',
            'enabled_modules' => 'array'
        ];
    }
    
    /**
     * Accessor untuk enabled_modules (jika field tidak ada di database)
     */
    public function getEnabledModulesAttribute($value)
    {
        if ($value === null) {
            // Jika field tidak ada di database, coba dari session
            $sessionKey = 'enabled_modules_' . $this->id;
            return session($sessionKey, ['invoice', 'products', 'crm', 'ecommerce']); // Default semua aktif
        }
        
        try {
            return json_decode($value, true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Mutator untuk enabled_modules
     */
    public function setEnabledModulesAttribute($value)
    {
        $this->attributes['enabled_modules'] = is_string($value) ? $value : json_encode($value);
    }

    /**
     * Accessor untuk avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        
        return null;
    }

    /**
     * Accessor untuk user initials
     */
    public function getInitialsAttribute()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        
        if (count($nameParts) > 0) {
            $initials .= strtoupper(substr($nameParts[0], 0, 1));
        }
        
        if (count($nameParts) > 1) {
            $initials .= strtoupper(substr($nameParts[1], 0, 1));
        }
        
        return $initials ?: 'U';
    }

    /**
     * Relationships
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function userTemplates(): HasMany
    {
        return $this->hasMany(UserTemplate::class);
    }

    /**
     * Methods for template management
     */
    public function hasTemplate($templateId): bool
    {
        // Cek apakah user memiliki template melalui UserTemplate
        $hasUserTemplate = $this->userTemplates()
            ->where('template_id', $templateId)
            ->exists();

        if ($hasUserTemplate) {
            return true;
        }

        // Cek apakah user memiliki template melalui Order yang paid
        return $this->orders()
            ->where('template_id', $templateId)
            ->where('status', 'paid')
            ->exists();
    }

    public function getOrCreateUserTemplate($templateId)
    {
        return $this->userTemplates()->firstOrCreate(
            ['template_id' => $templateId],
            ['is_active' => true]
        );
    }

    public function getActiveTemplate()
    {
        return $this->userTemplates()
                    ->where('is_active', true)
                    ->with('template')
                    ->first();
    }

    public function hasActiveTemplate()
    {
        return $this->userTemplates()
                    ->where('is_active', true)
                    ->exists();
    }

    public function activeTemplates()
    {
        return $this->userTemplates()
            ->where('is_active', true)
            ->with('template')
            ->get();
    }

    /**
     * Methods for module management
     */
    public function hasModuleEnabled($module): bool
    {
        $enabledModules = $this->enabled_modules;
        return in_array($module, $enabledModules);
    }

    public function enableModule($module): bool
    {
        $enabledModules = $this->enabled_modules;
        
        if (!in_array($module, $enabledModules)) {
            $enabledModules[] = $module;
            $this->enabled_modules = $enabledModules;
            return $this->save();
        }
        
        return true;
    }

    public function disableModule($module): bool
    {
        $enabledModules = $this->enabled_modules;
        $key = array_search($module, $enabledModules);
        
        if ($key !== false) {
            unset($enabledModules[$key]);
            $this->enabled_modules = array_values($enabledModules);
            return $this->save();
        }
        
        return true;
    }

    /**
     * Method untuk onboarding
     */
    public function completeOnboarding(): bool
    {
        $this->onboarding_completed = true;
        $this->onboarding_step = 'completed';
        return $this->save();
    }

    public function setOnboardingStep($step): bool
    {
        $this->onboarding_step = $step;
        return $this->save();
    }
}