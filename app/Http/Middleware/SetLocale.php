<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
// Uncomment and use Log for debugging if needed
// use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks if a 'locale' is set in the session.
     * If it is, and it's a supported locale, it sets the application's locale accordingly.
     * Otherwise, it defaults to the application's configured locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $defaultLocale = config('app.locale'); // Default locale from config/app.php
        $supportedLocalesConfig = config('app.supported_locales', []); // Supported locales from config/app.php

        // Check if a locale is stored in the session
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');

            // Check if the locale stored in the session is one of the supported locales
            if (array_key_exists($sessionLocale, $supportedLocalesConfig)) {
                // If supported, set it as the application's locale
                App::setLocale($sessionLocale);
                // Log::info("SetLocale Middleware: Locale set to '{$sessionLocale}' from session."); // For debugging
            } else {
                // If the locale in the session is not supported (rare, but good to handle)
                // Set the default locale and update the session to correct it
                App::setLocale($defaultLocale);
                Session::put('locale', $defaultLocale); // Update the session to the default
                // Log::warning("SetLocale Middleware: Unsupported locale '{$sessionLocale}' in session. Fallback to default: '{$defaultLocale}'."); // For debugging
            }
        } else {
            // If no locale is set in the session, Laravel will automatically use
            // the default locale specified in config('app.locale').
            // You could optionally set the default locale in the session here if desired:
            // Session::put('locale', $defaultLocale);
            // Log::info("SetLocale Middleware: No locale in session. Application will use default: '{$defaultLocale}'."); // For debugging
        }

        // Continue processing the request
        return $next($request);
    }
}
