<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XFrameOptionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request and get the response
        $response = $next($request);

        // Set the Content-Security-Policy header to allow iframe embedding from the specified URL
        $response->header('Content-Security-Policy', "frame-ancestors 'self' http://xadtechnologies.ddns.net:8087");

        return $response;
    }
}
