<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProtectFamilyData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Allow admin to access all family data
        if ($user && $user->role === 'admin') {
            return $next($request);
        }
        
        // For residents, only allow access to their own family data
        if ($user && $user->role === 'resident') {
            $familyId = $request->route('family') ?? $request->route('record');
            
            // If accessing specific family record, check ownership
            if ($familyId) {
                $userFamilyId = $user->family?->id;
                
                if (!$userFamilyId || $userFamilyId != $familyId) {
                    abort(403, 'Anda tidak memiliki akses ke data keluarga ini.');
                }
            }
        }
        
        return $next($request);
    }
}
