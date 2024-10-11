<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckIsModer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        if ((Auth::user()->status === 'Admin' || Auth::user()->status === 'admin')
            || (Auth::user()->status === 'Moder' || Auth::user()->status === 'moder')) {
            return $next($request);
        }

        // return redirect()->route('home');
        return Redirect::back()->withErrors(['msg' => 'You have no authority']);
    }
}
