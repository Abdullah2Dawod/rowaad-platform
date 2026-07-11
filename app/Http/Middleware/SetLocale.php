<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    protected array $supported = ['ar', 'en'];
    protected string $default = 'ar';

    public function handle(Request $request, Closure $next)
    {
        // Priority: 1) URL query ?lang=  2) Session  3) Cookie  4) Default
        $locale = $request->query('lang')
            ?? Session::get('locale')
            ?? $request->cookie('locale')
            ?? $this->default;

        if (!in_array($locale, $this->supported)) {
            $locale = $this->default;
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        $response = $next($request);
        if (method_exists($response, 'cookie')) {
            $response->cookie('locale', $locale, 60 * 24 * 365);
        }
        return $response;
    }
}
