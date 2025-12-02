<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function checkout($template)
    {
        // Handle both Template model and template ID
        if (!$template instanceof Template) {
            $template = Template::with('category')->findOrFail($template);
        }

        if (!$template->is_active) {
            abort(404);
        }

        // Jika template gratis, langsung proses
        if ($template->price == 0) {
            return $this->processFreeTemplate($template);
        }

        return view('payment.checkout', compact('template'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'payment_method' => 'required|in:bank_transfer,e_wallet',
        ]);

        $template = Template::findOrFail($request->template_id);

        // Calculate total amount
        $adminFee = 2500;
        $tax = $template->price * 0.1;
        $totalAmount = $template->price + $adminFee + $tax;

        // Create order
        $order = Order::create([
            'order_code' => 'TMP-' . date('Ymd') . '-' . Str::random(6),
            'user_id' => auth()->id(),
            'template_id' => $template->id,
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Redirect ke halaman instruksi pembayaran
        return redirect()->route('payment.instructions', $order);
    }

    public function instructions(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $template = $order->template;

        return view('payment.instructions', compact('order', 'template'));
    }

    public function uploadProof(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Upload bukti pembayaran
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            $order->update([
                'payment_proof' => $path,
                'status' => 'pending' // Tetap pending sampai admin verifikasi
            ]);

            // Tetap di halaman success, tidak redirect ke modules karena masih pending
            return redirect()->route('payment.success', $order)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }

    public function success(Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    $template = $order->template;

    // JIKA ORDER SUDAH PAID, AKTIFKAN TEMPLATE - PERBAIKAN INI
    if ($order->status === 'paid') {
        // Nonaktifkan template lama user
        UserTemplate::where('user_id', $order->user_id)
                   ->update(['is_active' => false]);
        
        // Buat/buat ulang user template record
        UserTemplate::updateOrCreate(
            [
                'user_id' => $order->user_id,
                'template_id' => $order->template_id
            ],
            [
                'is_active' => true,
                'activated_at' => now()
            ]
        );

        // Update order
        $order->update(['activated_at' => now()]);

        // Redirect langsung ke modules
        return redirect()->route('dashboard.modules')
            ->with('success', 'Pembayaran berhasil! Template Anda sekarang sudah aktif.');
    }

    return view('payment.success', compact('order', 'template'));
}

    private function processFreeTemplate(Template $template)
{
    // Create order untuk template gratis
    $order = Order::create([
        'order_code' => 'TMP-FREE-' . date('Ymd') . '-' . Str::random(6),
        'user_id' => auth()->id(),
        'template_id' => $template->id,
        'amount' => 0,
        'status' => 'paid',
        'payment_method' => 'free',
        'paid_at' => now(),
    ]);

    // Buat user template record
    UserTemplate::create([
        'user_id' => auth()->id(),
        'template_id' => $template->id,
        'is_active' => true,
        'activated_at' => now(),
    ]);

    // Redirect langsung ke editor untuk template gratis
    return redirect()->route('editor', ['template' => $template->id])
        ->with('success', 'Template gratis berhasil diaktifkan! Silakan customisasi template Anda.');
}
    
}