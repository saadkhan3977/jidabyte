<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check())
        {
            if(auth()->user()->role == 'admin'){
                return $next($request);
            }
            else{
                return redirect()->back()->with('error',"you don't have a permission");
            }
        }
        else{
            return redirect('partner/login');
        }
    }
}
