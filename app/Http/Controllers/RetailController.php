<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetailController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Data khusus untuk dashboard retail
        $data = [
            'sales_today' => 4500000,
            'inventory_value' => 12500000,
            'active_customers' => 342,
            'low_stock_items' => 15,
            'recent_transactions' => $this->getRecentTransactions(),
            'top_products' => $this->getTopProducts(),
            'sales_chart' => $this->getSalesChartData()
        ];
        
        return view('retail.dashboard', compact('data'));
    }
    
    public function inventory()
    {
        return view('retail.inventory');
    }
    
    public function pos()
    {
        return view('retail.pos');
    }
    
    public function reports()
    {
        return view('retail.reports');
    }
    
    public function customers()
    {
        return view('retail.customers');
    }
    
    private function getRecentTransactions()
    {
        return [
            ['id' => 'TRX001', 'customer' => 'John Doe', 'amount' => 250000, 'status' => 'completed'],
            ['id' => 'TRX002', 'customer' => 'Jane Smith', 'amount' => 450000, 'status' => 'completed'],
            ['id' => 'TRX003', 'customer' => 'Bob Johnson', 'amount' => 120000, 'status' => 'pending'],
        ];
    }
    
    private function getTopProducts()
    {
        return [
            ['name' => 'Produk A', 'sales' => 45, 'revenue' => 2250000],
            ['name' => 'Produk B', 'sales' => 38, 'revenue' => 1900000],
            ['name' => 'Produk C', 'sales' => 32, 'revenue' => 1600000],
        ];
    }
    
    private function getSalesChartData()
    {
        return [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data' => [4500, 5200, 4800, 6100, 7300, 8200, 6900]
        ];
    }
}