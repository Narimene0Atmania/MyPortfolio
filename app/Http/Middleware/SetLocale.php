<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! is_string($locale) || ! array_key_exists($locale, config('portfolio.locales'))) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        // every route() call in the app inherits the current locale, so
        // links stay in the language of the page they're on without each
        // call site having to pass it
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
