<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // https://stackoverflow.com/questions/15389833/laravel-redirect-back-to-original-destination-after-login
        // take user back to intended page

        if (Auth::guard($guard)->check()) {

          // original page may need
          // Session::put('url.intended', URL::full());
          return redirect()->intended('/');

          // original
          //return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
