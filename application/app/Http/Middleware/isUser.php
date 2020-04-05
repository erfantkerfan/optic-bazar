<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isUser
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
        if(!Auth::check()){
            return redirect("/login")->with('error' , 'جهت مشاهده این بخش وارد حساب کاربری خود شوید.');
        }

        if(Auth::user()->status == "inactive"){
            Auth::logout();
            return redirect("/login")->with('error' , 'حساب کاربری شما غیر فعال می باشد.');
        }

        if(Auth::user()->status == "not_verified"){
            return redirect("/conferm-account");
        }

        if(Auth::user()->status == "not_active"){
            return redirect("/user/checking-account");
        }

        return $next($request);
    }
}
