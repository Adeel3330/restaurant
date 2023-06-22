<?php

namespace App\Http\Middleware;

use Closure;
use session;
use Illuminate\Http\Request;
// use Illuminate\Contracts\Session;
use Symfony\Component\HttpFoundation\Response;

class LoginApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->has('id')){
            return $next($request);
        }else
        {
            return response()->json([
                "message"=>"Please login first"
            ],420);
        }
        
    }
}
