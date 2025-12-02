<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\Category;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\BusinessModule;
use App\Models\Webinar;
use App\Models\Customer;
use App\Models\Integration;
use App\Models\IntegrationConnection;
use App\Models\Certificate;
use App\Models\UserTemplate;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Debug: Check user template
        Log::info('Dashboard Debug - User ID: ' . $userId);
        
        $userTemplates = UserTemplate::where('user_id', $userId)->get();
        $activeTemplate = UserTemplate::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
        
        Log::info('User Template Info:', [
            'total_templates' => $userTemplates->count(),
            'active_template_id' => $activeTemplate?->template_id,
            'active_template_name' => $activeTemplate?->template?->name,
        ]);

        // AMBIL DATA REAL dari database - FILTER BY USER
        $stats = [
            'total_sales' => Invoice::where('status', 'paid')
                ->where('user_id', $userId)
                ->sum('amount') ?? 0,
            
            'monthly_transactions' => Invoice::where('status', 'paid')
                ->where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            
            'low_stock' => Product::where('user_id', $userId)
                ->whereColumn('stock', '<=', 'min_stock')
                ->count(),
            
            // PERBAIKAN: Hitung modul yang TERINSTAL untuk user ini
            'active_modules' => $this->getUserActiveModulesCount($userId),
        ];

        // Log statistik
        Log::info('Dashboard stats for user ' . $userId, $stats);

        // HITUNG PERSENTASE REAL berdasarkan data sebelumnya
        $previousMonthSales = Invoice::where('status', 'paid')
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');
        
        $salesGrowth = 0;
        if ($previousMonthSales > 0 && $stats['total_sales'] > 0) {
            $salesGrowth = (($stats['total_sales'] - $previousMonthSales) / $previousMonthSales) * 100;
        } elseif ($stats['total_sales'] > 0) {
            $salesGrowth = 100; // Jika sebelumnya 0 dan sekarang ada penjualan
        }

        $previousMonthTransactions = Invoice::where('status', 'paid')
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $transactionGrowth = 0;
        if ($previousMonthTransactions > 0 && $stats['monthly_transactions'] > 0) {
            $transactionGrowth = (($stats['monthly_transactions'] - $previousMonthTransactions) / $previousMonthTransactions) * 100;
        } elseif ($stats['monthly_transactions'] > 0) {
            $transactionGrowth = 100;
        }

        // DATA UNTUK CHART - REAL DATA dengan filter user
        $salesData = $this->getSalesChartData($userId);
        $chartData = [
            'labels' => $salesData['labels'],
            'data' => $salesData['data'],
            'growth' => round($salesGrowth, 1)
        ];

        // Quick modules DARI TEMPLATE USER, bukan semua modul
        $quickAccess = $this->getUserQuickAccessModules($userId);

        // Jika tidak ada modul dari template, gunakan fallback
        if (empty($quickAccess)) {
            $quickAccess = [
                [
                    'title' => 'Manajemen Produk',
                    'description' => 'Kelola produk & inventory',
                    'icon' => 'fas fa-box',
                    'bg_color' => 'bg-primary-50',
                    'icon_color' => 'text-primary'
                ],
                [
                    'title' => 'Invoice & Keuangan',
                    'description' => 'Kelola invoice dan pembayaran',
                    'icon' => 'fas fa-file-invoice',
                    'bg_color' => 'bg-green-50', 
                    'icon_color' => 'text-green-600'
                ],
                [
                    'title' => 'Katalog Produk',
                    'description' => 'Data produk dan kategori',
                    'icon' => 'fas fa-tags',
                    'bg_color' => 'bg-blue-50',
                    'icon_color' => 'text-blue-600'
                ],
                [
                    'title' => 'Manajemen Pelanggan',
                    'description' => 'Data & riwayat pelanggan',
                    'icon' => 'fas fa-users',
                    'bg_color' => 'bg-purple-50',
                    'icon_color' => 'text-purple-600'
                ]
            ];
            
            Log::info('Using fallback quick access modules for user ' . $userId);
        }

        $recentWebinars = Webinar::where(function($query) {
                $query->where('status', 'live')
                      ->orWhere('status', 'upcoming');
            })
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return view('dashboard.index', compact(
            'stats', 
            'quickAccess', 
            'recentWebinars', 
            'chartData',
            'salesGrowth',
            'transactionGrowth'
        ));
    }

    /**
     * Get count of active modules for specific user
     */
    private function getUserActiveModulesCount($userId)
    {
        try {
            // Cek template aktif user
            $activeTemplate = UserTemplate::where('user_id', $userId)
                ->where('is_active', true)
                ->first();
            
            if (!$activeTemplate) {
                Log::warning('User ' . $userId . ' has no active template for module count');
                return 0; // Tidak ada template aktif, berarti tidak ada modul
            }
            
            // Hitung modul untuk template aktif user
            $modulesCount = BusinessModule::where('template_id', $activeTemplate->template_id)
                ->where('is_active', true)
                ->count();
                
            Log::info('User ' . $userId . ' has ' . $modulesCount . ' modules for template ' . $activeTemplate->template_id);
            
            return $modulesCount;
            
        } catch (\Exception $e) {
            Log::error('Error counting user modules: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get quick access modules for specific user
     */
    private function getUserQuickAccessModules($userId)
    {
        try {
            // Cek template aktif user
            $activeTemplate = UserTemplate::where('user_id', $userId)
                ->where('is_active', true)
                ->first();
            
            if (!$activeTemplate) {
                Log::warning('User ' . $userId . ' has no active template for quick access modules');
                return [];
            }
            
            // Ambil modul dari template aktif
            $modules = BusinessModule::where('template_id', $activeTemplate->template_id)
                ->where('is_active', true)
                ->orderBy('order')
                ->limit(4)
                ->get();
            
            Log::info('Quick access modules for user ' . $userId . ': ' . $modules->count() . ' modules found');
            
            return $modules->map(function($module) {
                return [
                    'title' => $module->name,
                    'description' => $module->description,
                    'icon' => $module->icon,
                    'bg_color' => $module->bg_color,
                    'icon_color' => $module->icon_color
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            Log::error('Error getting user quick access modules: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get REAL sales data for chart dengan filter user
     */
    private function getSalesChartData($userId)
    {
        $months = [];
        $sales = [];
        
        // Generate last 12 months dengan data REAL
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');
            
            // Get REAL sales data untuk bulan ini dengan filter user
            $monthSales = Invoice::where('status', 'paid')
                ->where('user_id', $userId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
                
            $sales[] = $monthSales ?? 0;
        }
        
        // Debug log
        Log::info('Sales chart data for user ' . $userId, [
            'months' => $months,
            'sales' => $sales
        ]);
        
        return [
            'labels' => $months,
            'data' => $sales
        ];
    }

    public function templates(Request $request)
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
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $templates = $query->paginate(12);
    
        // PERBAIKAN: Ambil hanya nama kategori untuk dropdown
        $categories = Category::where('is_active', true)
            ->pluck('name', 'id') // Ambil name sebagai value, id sebagai key
            ->toArray();

        // Get user's owned templates
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

        return view('dashboard.templates', compact(
            'templates', 
            'categories', 
            'userTemplates',
            'activePlan'
        ));
    }
    
    public function modules()
{
    $user = auth()->user();
    
    Log::info('User accessing modules page: ' . $user->id);
    
    // CEK TEMPLATE AKTIF USER
    $activeTemplate = UserTemplate::with('template')
        ->where('user_id', $user->id)
        ->where('is_active', true)
        ->first();

    if (!$activeTemplate) {
        Log::warning('User ' . $user->id . ' has no active template when accessing modules');
        
        return redirect()->route('dashboard.templates')
                        ->with('info', 'Silakan pilih dan aktivasi template terlebih dahulu');
    }

    Log::info('User ' . $user->id . ' active template: ' . $activeTemplate->template_id);
    
    // CEK MODUL YANG TERSEDIA UNTUK TEMPLATE INI
    $availableModules = BusinessModule::where('template_id', $activeTemplate->template_id)
        ->where('is_active', true)
        ->get();

    Log::info('Available modules for template ' . $activeTemplate->template_id . ': ' . $availableModules->count());
    
    // SOLUSI 2: Gunakan session jika field tidak ada di database
    // Cek jika user punya field enabled_modules, jika tidak gunakan session
    $enabledModules = [];
    
    if (isset($user->enabled_modules)) {
        // Jika field ada di database
        $enabledModules = $user->enabled_modules ?? [];
    } else {
        // Jika field tidak ada, gunakan session
        $enabledModules = session('enabled_modules_' . $user->id, []);
    }
    
    // Data untuk setiap tab/modul
    $invoices = Invoice::where('user_id', $user->id)->get();
    $products = Product::where('user_id', $user->id)->get();
    $customers = Customer::where('user_id', $user->id)->get();
    $orders = Order::where('user_id', $user->id)->get();

    // Prepare module status
    $modules = [
        'invoice' => [
            'title' => 'Invoice & Keuangan',
            'description' => 'Kelola invoice dan pembayaran pelanggan',
            'is_enabled' => in_array('invoice', $enabledModules) || $availableModules->contains('name', 'Invoice') || true, // Default enabled
            'has_data' => $invoices->count() > 0,
            'data_count' => $invoices->count(),
            'stats' => [
                'total' => $invoices->count(),
                'paid' => $invoices->where('status', 'paid')->sum('amount') ?? 0,
                'pending' => $invoices->where('status', 'pending')->sum('amount') ?? 0,
                'overdue' => $invoices->where('status', 'overdue')->sum('amount') ?? 0,
            ]
        ],
        'products' => [
            'title' => 'Katalog Produk',
            'description' => 'Kelola produk dan stok inventori',
            'is_enabled' => in_array('products', $enabledModules) || $availableModules->contains('name', 'Products') || true,
            'has_data' => $products->count() > 0,
            'data_count' => $products->count(),
            'stats' => [
                'total' => $products->count(),
                'inventory_value' => $products->sum('price') ?? 0,
                'low_stock' => $products->where('stock', '<=', 5)->count(),
                'categories' => $products->pluck('category_id')->unique()->count(),
            ]
        ],
        'crm' => [
            'title' => 'Customer Relationship Management',
            'description' => 'Kelola hubungan dengan pelanggan',
            'is_enabled' => in_array('crm', $enabledModules) || $availableModules->contains('name', 'CRM') || true,
            'has_data' => $customers->count() > 0,
            'data_count' => $customers->count(),
            'stats' => [
                'total' => $customers->count(),
                'vip' => $customers->where('type', 'vip')->count(),
                'revenue' => $customers->sum('total_spent') ?? 0,
                'new_customers' => $customers->where('created_at', '>=', now()->subDays(30))->count(),
            ]
        ],
        'ecommerce' => [
            'title' => 'Template & Orders',
            'description' => 'Kelola template dan pesanan',
            'is_enabled' => in_array('ecommerce', $enabledModules) || $availableModules->contains('name', 'E-commerce') || true,
            'has_data' => $orders->count() > 0,
            'data_count' => $orders->count(),
            'stats' => [
                'total' => $orders->count(),
                'completed' => $orders->where('status', 'paid')->count(),
                'processing' => $orders->where('status', 'pending')->count(),
                'revenue' => $orders->sum('amount') ?? 0,
            ]
        ]
    ];

    return view('dashboard.modules', compact(
        'modules', 'invoices', 'products', 'customers', 'orders', 'activeTemplate'
    ));
}


public function enableModule(Request $request)
{
    try {
        $request->validate([
            'module' => 'required|string|in:invoice,products,crm,ecommerce'
        ]);

        $user = auth()->user();
        
        // SOLUSI 2: Cek jika field ada di database atau gunakan session
        if (isset($user->enabled_modules)) {
            // Database approach
            $enabledModules = $user->enabled_modules ?? [];
            
            if (!in_array($request->module, $enabledModules)) {
                $enabledModules[] = $request->module;
                $user->enabled_modules = $enabledModules;
                $user->save();
            }
        } else {
            // Session approach
            $sessionKey = 'enabled_modules_' . $user->id;
            $enabledModules = session($sessionKey, []);
            
            if (!in_array($request->module, $enabledModules)) {
                $enabledModules[] = $request->module;
                session([$sessionKey => $enabledModules]);
            }
        }
        
        Log::info('User ' . $user->id . ' enabled module: ' . $request->module);
        
        return response()->json([
            'success' => true,
            'message' => 'Module berhasil diaktifkan',
            'module' => $request->module
        ]);

    } catch (\Exception $e) {
        Log::error('Error enabling module: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengaktifkan module'
        ], 500);
    }
}

/**
 * Disable a module (with session fallback)
 */
public function disableModule(Request $request)
{
    try {
        $request->validate([
            'module' => 'required|string|in:invoice,products,crm,ecommerce'
        ]);

        $user = auth()->user();
        
        // SOLUSI 2: Cek jika field ada di database atau gunakan session
        if (isset($user->enabled_modules)) {
            // Database approach
            $enabledModules = $user->enabled_modules ?? [];
            
            $key = array_search($request->module, $enabledModules);
            if ($key !== false) {
                unset($enabledModules[$key]);
                $enabledModules = array_values($enabledModules);
                $user->enabled_modules = $enabledModules;
                $user->save();
            }
        } else {
            // Session approach
            $sessionKey = 'enabled_modules_' . $user->id;
            $enabledModules = session($sessionKey, []);
            
            $key = array_search($request->module, $enabledModules);
            if ($key !== false) {
                unset($enabledModules[$key]);
                $enabledModules = array_values($enabledModules);
                session([$sessionKey => $enabledModules]);
            }
        }
        
        Log::info('User ' . $user->id . ' disabled module: ' . $request->module);
        
        return response()->json([
            'success' => true,
            'message' => 'Module berhasil dinonaktifkan',
            'module' => $request->module
        ]);

    } catch (\Exception $e) {
        Log::error('Error disabling module: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal menonaktifkan module'
        ], 500);
    }
}

    public function integrations()
    {
        $userId = Auth::id();

        // Ambil data integrations dari database menggunakan MODEL
        $integrations = Integration::where('is_active', true)->get();

        // Ambil data koneksi user menggunakan MODEL dengan relasi
        $userIntegrations = IntegrationConnection::with('integration')
            ->where('user_id', $userId)
            ->get();

        // Hitung statistik
        $statistics = [
            'connected' => $userIntegrations->where('connection_status', 'connected')->count(),
            'pending' => $userIntegrations->where('connection_status', 'pending')->count(),
            'not_connected' => $integrations->count() - $userIntegrations->count()
        ];

        // Aktivitas terbaru
        $activities = IntegrationConnection::with('integration')
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.integrations', compact('integrations', 'userIntegrations', 'statistics', 'activities'));
    }

    public function workshop()
    {
        $userId = Auth::id();

        // Ambil data webinars dari database menggunakan MODEL
        $webinars = Webinar::orderBy('start_date')->get();

        // Pisahkan berdasarkan status
        $liveWebinars = $webinars->where('status', 'live');
        $upcomingWebinars = $webinars->where('status', 'upcoming');

        // Data tutorial (hardcoded untuk sekarang)
        $tutorials = [
            [
                'title' => 'Panduan Menggunakan Dashboard',
                'duration' => '15 menit',
                'level' => 'Pemula'
            ],
            [
                'title' => 'Cara Integrasi dengan Marketplace',
                'duration' => '25 menit', 
                'level' => 'Menengah'
            ]
        ];

        // Data certificates user menggunakan MODEL
        $certificates = Certificate::where('user_id', $userId)->get();

        return view('dashboard.workshop', compact('liveWebinars', 'upcomingWebinars', 'tutorials', 'certificates'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.settings', compact('user'));
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
}