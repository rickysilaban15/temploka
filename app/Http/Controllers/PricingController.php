<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use App\Models\Template;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        // Gunakan scope active() dari model
        $plans = PricingPlan::active()->get()->map(function($plan) {
            // Pastikan features adalah array
            if (is_string($plan->features)) {
                $plan->features = json_decode($plan->features, true) ?? [];
            }
            
            if (!is_array($plan->features)) {
                $plan->features = [];
            }
            
            return $plan;
        });

        return view('templates.pricing', compact('plans'));
    }

    public function show($slug)
    {
        $plan = PricingPlan::where('slug', $slug)->firstOrFail();
        
        if (is_string($plan->features)) {
            $plan->features = json_decode($plan->features, true) ?? [];
        }
        
        if (!is_array($plan->features)) {
            $plan->features = [];
        }
        
        return view('templates.pricing.show', compact('plan'));
    }

    /**
     * Redirect ke templates dengan filter berdasarkan plan yang dipilih
     * Handle kedua jenis route (public dan dashboard)
     */
    public function redirectToTemplates(Request $request, $planId)
    {
        $plan = PricingPlan::findOrFail($planId);
        
        // Tentukan range harga berdasarkan plan
        $priceRange = $this->getPriceRangeForPlan($plan);
        
        // Tentukan route tujuan berdasarkan status login
        $routeName = auth()->check() ? 'dashboard.templates' : 'templates.index';
        
        // Redirect ke templates dengan parameter filter
        return redirect()->route($routeName, [
            'price_min' => $priceRange['min'],
            'price_max' => $priceRange['max'],
            'plan' => $plan->slug
        ]);
    }

    /**
     * Tentukan range harga template berdasarkan plan yang dipilih
     */
    private function getPriceRangeForPlan(PricingPlan $plan)
    {
        // Logic untuk menentukan range harga berdasarkan plan
        switch($plan->name) {
            case 'Free':
                return ['min' => 0, 'max' => 0];
                
            case 'Starter':
                return ['min' => 0, 'max' => 50000];
                
            case 'Professional':
                return ['min' => 50000, 'max' => 200000];
                
            case 'Enterprise':
                return ['min' => 200000, 'max' => 1000000];
                
            default:
                return ['min' => 0, 'max' => 0];
        }
    }
}