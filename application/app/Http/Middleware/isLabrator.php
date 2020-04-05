<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class isLabrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() &&  Auth::user()->role == "labrator") {
            return $next($request);
        }

        if(!Auth::check()){
            return redirect("/login")->with('error' , 'جهت مشاهده این بخش وارد حساب کاربری خود شوید.');
        }

        return App::abort(404);
    }
}
