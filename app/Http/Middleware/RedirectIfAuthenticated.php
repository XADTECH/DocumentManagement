<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    // Check if the user is authenticated
    if (Auth::check()) {
      // Redirect to the dashboard if already authenticated
      return redirect()->route('dashboard-analytics');
    }

    // Proceed with the request if not authenticated
    return $next($request);
  }
}
