<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $csp = "default-src 'self';";
        $csp .= "script-src 'self' 'unsafe-inline';";
        $csp .= "script-src-elem 'self' 'unsafe-inline' http://127.0.0.1:5174;";
        $csp .= "style-src 'self' 'unsafe-inline';";
        $csp .= "style-src-elem 'self' 'unsafe-inline' http://127.0.0.1:5174;";
        $csp .= "img-src 'self' data: https://picsum.photos;";
        $csp .= "font-src 'self';";
        $csp .= "connect-src 'self';";
        $csp .= "frame-src 'self';";
        $csp .= "object-src 'none';";
        $csp .= "base-uri 'self';";
        $csp .= "form-action 'self';";
        $csp .= "upgrade-insecure-requests;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
