<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('templates.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you can add email sending logic
        // Mail::to('hello@temploka.com')->send(new ContactMail($request->all()));

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}