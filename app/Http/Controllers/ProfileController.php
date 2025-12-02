<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('dashboard.settings', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|string',
        ]);

        try {
            $user->update($validated);

            return redirect()->route('dashboard.settings')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Handle AJAX avatar upload
     */
   public function uploadAvatar(Request $request)
{
    \Log::info('Avatar upload started', ['user_id' => Auth::id()]);
    
    try {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        
        \Log::info('User found', ['user_id' => $user->id]);

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            \Log::info('Old avatar deleted', ['avatar' => $user->avatar]);
        }

        // ✅ PERBAIKI: Upload ke folder 'avatars' di public disk
        $file = $request->file('avatar');
        $filename = 'avatars/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        \Log::info('Storing file', ['filename' => $filename]);
        
        // ✅ PERBAIKI: Gunakan storeAs dengan public disk
        $path = $file->storeAs('avatars', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
        
        \Log::info('File stored', ['path' => $path]);

        // Update user avatar - simpan relative path
        $user->update([
            'avatar' => $path
        ]);

        \Log::info('User avatar updated', ['avatar' => $path]);

        // ✅ PERBAIKI: Gunakan asset() untuk generate URL
        $avatarUrl = asset('storage/' . $path);

        \Log::info('Avatar URL generated', ['url' => $avatarUrl]);

        return response()->json([
            'success' => true,
            'avatar_path' => $path,
            'avatar_url' => $avatarUrl,
            'message' => 'Avatar berhasil diupload'
        ]);

    } catch (\Exception $e) {
        \Log::error('Avatar upload error', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload avatar: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Handle AJAX avatar removal
     */
    public function removeAvatar()
{
    try {
        $user = Auth::user();
        
        if ($user->avatar) {
            // Delete file from storage
            Storage::disk('public')->delete($user->avatar);
            
            // Remove avatar from user
            $user->update(['avatar' => null]);
            
            \Log::info('Avatar removed', ['user_id' => $user->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil dihapus'
        ]);

    } catch (\Exception $e) {
        \Log::error('Remove avatar error', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus avatar: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}