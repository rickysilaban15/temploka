<?php
// app/Http/Middleware/CheckTemplateAccess.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserTemplate;

class CheckTemplateAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!UserTemplate::where('user_id', $user->id)->where('is_active', true)->exists()) {
            return redirect()->route('dashboard.templates')
                            ->with('error', 'Anda perlu mengaktifkan template terlebih dahulu untuk mengakses modul.');
        }
        
        return $next($request);
    }
}