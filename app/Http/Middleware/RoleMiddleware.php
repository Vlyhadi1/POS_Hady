<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // Cek login
        if (!auth()->check()) {

            return redirect()->route('login');

        }


        // Ambil role user dengan aman
        $userRole = strtolower(
            optional(auth()->user()->role)->name ?? ''
        );


        // Ubah role yang diizinkan menjadi huruf kecil
        $allowedRoles = array_map(function ($role) {

            return strtolower($role);

        }, $roles);



        // Jika role tidak cocok
        if (!in_array($userRole, $allowedRoles)) {

            abort(403, 'Unauthorized');

        }


        return $next($request);
    }
}