<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAjax
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
        if($request->ajax()){
            return $next($request);
        }else{
            return redirect()->back();
        }
    }
}
