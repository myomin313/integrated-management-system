<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FortiClientMiddleware
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
        $host = request()->getHost();
        return $next($request);
        if($host=="marubeniyangon.com.mm"){
            $agent = new \Jenssegers\Agent\Agent;
   
            if($agent->isDesktop()){
                $ip = \Request::ip();
                if($ip="136.228.161.174"){
                    return $next($request);
                }
                else{
                    return response()->view("not_access");
                }
            }
            else{
                return redirect("https://mobile.marubeniyangon.com.mm");
            }
            
        }
        else{
            $agent = new \Jenssegers\Agent\Agent;
            if($agent->isDesktop()){
                return redirect("https://marubeniyangon.com.mm");
            }
            else{
                return $next($request);
                
            }
        }

        return $next($request);
    }
}
