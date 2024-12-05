<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckLogin
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
    // Check if the user is not authenticated
    if (!Auth::check()) {
      // Redirect to the login page if not authenticated
      return redirect()->route(route: 'auth-login-basic');
    }

    // Proceed with the request if authenticated
    return $next($request);
  }
}
