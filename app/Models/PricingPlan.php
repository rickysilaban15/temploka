<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_period',
        'discount_percentage',
        'features',
        'template_limit',
        'storage_gb',
        'priority_support',
        'custom_domain',
        'white_label',
        'api_access',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'priority_support' => 'boolean',
        'custom_domain' => 'boolean',
        'white_label' => 'boolean',
        'api_access' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.');
    }

    // Get price after discount
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return $this->price;
    }

    // Get formatted discounted price
    public function getFormattedDiscountedPriceAttribute()
    {
        return number_format($this->discounted_price, 0, ',', '.');
    }

    // Scope for active plans
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    // Scope for featured plans
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}