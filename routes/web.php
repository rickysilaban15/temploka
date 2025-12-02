<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\TemplateAdminController;
use App\Http\Middleware\CheckTemplateAccess;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::get('/', [TemplateController::class, 'index'])->name('home');
Route::get('/templates', [TemplateController::class, 'browse'])->name('templates.index');
Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');
Route::get('/templates/preview/{template}', [TemplateController::class, 'preview'])
    ->name('templates.preview');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// API untuk filter templates
Route::get('/api/templates/filter', [TemplateController::class, 'getTemplatesByFilter'])
    ->name('templates.filter');

// Pricing Routes - Public
Route::get('/harga', [PricingController::class, 'index'])->name('pricing');
Route::get('/harga/{slug}', [PricingController::class, 'show'])->name('pricing.show');
Route::get('/harga/{plan}/templates', [PricingController::class, 'redirectToTemplates'])->name('pricing.templates');

// Support Routes
Route::get('/pusat-bantuan', [SupportController::class, 'helpCenter'])->name('help-center');
Route::get('/dokumentasi', [SupportController::class, 'documentation'])->name('documentation');
Route::get('/tutorial', [SupportController::class, 'tutorials'])->name('tutorials');
Route::get('/fitur', [FeatureController::class, 'index'])->name('features');
Route::get('/integrasi', [IntegrationController::class, 'index'])->name('integrations.index');

// Contact Routes
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'send'])->name('contact.send');

// AUTH ROUTES
require __DIR__.'/auth.php';

// PROTECTED ROUTES (Setelah Login)
Route::middleware(['auth'])->group(function () {
    // Onboarding Routes
    Route::middleware(['onboarding.incomplete'])->group(function () {
        Route::get('/onboarding', [OnboardingController::class, 'step1'])->name('onboarding.step1');
        Route::post('/onboarding/store-business-type', [OnboardingController::class, 'storeBusinessType'])->name('onboarding.storeBusinessType');
        
        Route::get('/onboarding/step2', [OnboardingController::class, 'step2'])->name('onboarding.step2');
        Route::post('/onboarding/store-goals', [OnboardingController::class, 'storeGoals'])->name('onboarding.storeGoals');
        
        Route::get('/onboarding/step3', [OnboardingController::class, 'step3'])->name('onboarding.step3');
        Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
    });

    // Dashboard & App Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/templates', [DashboardController::class, 'templates'])->name('dashboard.templates');
    Route::get('/dashboard/modules', [DashboardController::class, 'modules'])
        ->name('dashboard.modules')
        ->middleware(CheckTemplateAccess::class);
    Route::get('/dashboard/integrations', [DashboardController::class, 'integrations'])->name('dashboard.integrations');
    Route::get('/dashboard/workshop', [DashboardController::class, 'workshop'])->name('dashboard.workshop');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
    Route::prefix('dashboard')->group(function () {
    Route::post('/modules/enable', [DashboardController::class, 'enableModule'])->name('dashboard.modules.enable');
    Route::post('/modules/disable', [DashboardController::class, 'disableModule'])->name('dashboard.modules.disable');
});
    // PROFILE ROUTES
    Route::prefix('dashboard')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Avatar upload routes
        Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
        Route::post('/profile/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
    });

    // Template Usage Routes
    Route::get('/templates/{template}/use', [TemplateController::class, 'useTemplate'])
        ->name('templates.use');

    // Route untuk purchase template
    Route::post('/templates/{id}/purchase', [TemplateController::class, 'purchase'])
        ->name('templates.purchase');
    
    // Payment Routes
    Route::get('/payment/checkout/{template}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/instructions/{order}', [PaymentController::class, 'instructions'])->name('payment.instructions');
    Route::post('/payment/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload-proof');

    // Editor Routes - FIXED
    Route::prefix('editor')->group(function () {
        Route::get('/{template?}', [EditorController::class, 'index'])->name('editor.index');
        Route::get('/preview/{template}', [EditorController::class, 'preview'])->name('editor.preview');
        
        // API Endpoints
        Route::post('/save', [EditorController::class, 'save'])->name('editor.save');
        Route::post('/publish', [EditorController::class, 'publish'])->name('editor.publish');
        Route::post('/upload-image', [EditorController::class, 'uploadImage'])->name('editor.upload-image');
        Route::post('/reset', [EditorController::class, 'reset'])->name('editor.reset');
        Route::post('/duplicate', [EditorController::class, 'duplicate'])->name('editor.duplicate');
        Route::post('/delete', [EditorController::class, 'delete'])->name('editor.delete');
        Route::get('/get-template-content/{template}', [EditorController::class, 'getTemplateContentApi'])->name('editor.get-content');
        
        // Get published templates
        Route::get('/published/list', [EditorController::class, 'getPublishedTemplates'])->name('editor.published.list');
    });

    // Published templates view (public access)
    Route::get('/templates/published/{user}/{template}', [EditorController::class, 'viewPublished'])
        ->name('templates.published');
    
    // Debug route
    Route::get('/debug/template/{id}', [EditorController::class, 'debugTemplate'])->name('debug.template');

    // Integration Routes
    Route::get('/app-redirect', function () {
        if (auth()->check() && !auth()->user()->onboarding_completed) {
            return redirect()->route('onboarding.step1');
        }
        return redirect()->route('dashboard');
    })->name('app.redirect');
    
    // Admin Template Management Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/templates', [TemplateAdminController::class, 'index'])->name('admin.templates.index');
        Route::get('/templates/create', [TemplateAdminController::class, 'create'])->name('admin.templates.create');
        Route::post('/templates', [TemplateAdminController::class, 'store'])->name('admin.templates.store');
        Route::get('/templates/{id}/edit', [TemplateAdminController::class, 'edit'])->name('admin.templates.edit');
        Route::put('/templates/{id}', [TemplateAdminController::class, 'update'])->name('admin.templates.update');
        Route::delete('/templates/{id}', [TemplateAdminController::class, 'destroy'])->name('admin.templates.destroy');
        
        // Route untuk upload template via ZIP
        Route::get('/templates/upload', [TemplateAdminController::class, 'uploadForm'])->name('admin.templates.upload.form');
        Route::post('/templates/upload', [TemplateAdminController::class, 'upload'])->name('admin.templates.upload');
    });
});

// Public preview route untuk non-authenticated users
Route::get('/preview/template/{user}/{template}', [EditorController::class, 'viewPublished'])
    ->name('templates.public-preview');

// Fallback Route untuk 404
Route::fallback(function () {
    return view('errors.404');
});