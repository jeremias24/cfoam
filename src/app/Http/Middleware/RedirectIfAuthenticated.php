<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {

        // return $next($request)
        // ->header('Access-Control-Allow-Origin', 'http://yourfrontenddomain.com') // maybe put this into the .env file so you can change the URL in production.
        // ->header('Access-Control-Allow-Methods', '*') // or specify `'GET, POST, PUT, DELETE'` etc as the second parameter if you want to restrict the methods that are allowed.
        // ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization') // or add your headers.

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
