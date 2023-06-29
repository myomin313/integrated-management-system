<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangePasswordProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->password_change==0) {
            return redirect("master-management/user/change-password");
        }
        else if(auth()->user()->profile_add==0){
            return redirect("master-management/user/profile");
        }
  
        return $next($request);
    }
}
