<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
// يمكنك إضافة هذا إذا أردت تسجيل معلومات للتحقق لاحقاً
// use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $defaultLocale = config('app.locale'); // اللغة الافتراضية من config/app.php
        $supportedLocalesConfig = config('app.supported_locales', []); // اللغات المدعومة من config/app.php

        // تحقق إذا كانت هناك لغة مخزنة في الجلسة
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');

            // تحقق إذا كانت اللغة المخزنة في الجلسة هي واحدة من اللغات المدعومة
            if (array_key_exists($sessionLocale, $supportedLocalesConfig)) {
                // إذا كانت مدعومة، قم بتعيينها كلغة للتطبيق
                App::setLocale($sessionLocale);
                // Log::info("Locale set from session: " . $sessionLocale); // للتحقق
            } else {
                // إذا كانت اللغة في الجلسة غير مدعومة (نادر، لكن جيد التحقق)
                // قم بتعيين اللغة الافتراضية وحدث الجلسة
                App::setLocale($defaultLocale);
                Session::put('locale', $defaultLocale); // حدث الجلسة لتصحيحها
                // Log::warning("Unsupported locale '{$sessionLocale}' in session. Fallback to default: " . $defaultLocale); // للتحقق
            }
        } else {
            // إذا لم تكن هناك لغة في الجلسة، لا تفعل شيئًا،
            // سيستخدم Laravel اللغة الافتراضية المحددة في config('app.locale') تلقائيًا.
            // يمكنك اختيار وضع اللغة الافتراضية في الجلسة هنا إذا أردت:
            // Session::put('locale', $defaultLocale);
            // Log::info("No locale in session. Using default: " . $defaultLocale); // للتحقق
        }

        // اسمح للطلب بالاستمرار
        return $next($request);
    }
}
