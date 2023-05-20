<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
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
        $default_language = Language::where('default', '=', 1)->first();
        if(Session::get('currentLocal')){
            $currentLocale = Session::get('currentLocal');
            Session::put('currentLocal', $currentLocale);
        }else {
            $currentLocale = $default_language->local ?? 'en';
            Session::put('currentLocal', $currentLocale);
        }
        App::setLocale($currentLocale);
        return $next($request);
    }
}
