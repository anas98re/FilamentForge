<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;      // <--- تأكد من هذا الاستيراد
use Illuminate\Support\Facades\Redirect; // <--- تأكد من هذا الاستيراد

class LanguageSwitcher extends Component
{
    public string $currentLocale;
    public array $supportedLocales;

    public function mount()
    {
        // عند تحميل المكون لأول مرة، اقرأ اللغة من الجلسة.
        // إذا لم تكن موجودة في الجلسة، استخدم اللغة الافتراضية من إعدادات التطبيق.
        $this->currentLocale = Session::get('locale', config('app.locale'));

        // تحميل اللغات المدعومة من ملف الإعدادات
        $this->supportedLocales = config('app.supported_locales', [
            'en' => 'English',
            'ar' => 'العربية',
        ]);
    }

    public function switchLocale(string $localeToSwitchTo)
    {
        // تحقق أن اللغة المطلوبة موجودة ضمن اللغات المدعومة
        if (array_key_exists($localeToSwitchTo, $this->supportedLocales)) {

            // 1. ضع اللغة الجديدة في الجلسة ليتم استخدامها في الطلبات القادمة
            Session::put('locale', $localeToSwitchTo);

            // 2. قم بتغيير لغة التطبيق فوراً للطلب الحالي (اختياري ولكن جيد)
            //    هذا يساعد إذا كان هناك أي منطق يعتمد على اللغة قبل إعادة التوجيه.
            App::setLocale($localeToSwitchTo);

            // 3. قم بتحديث الخاصية currentLocale في هذا المكون لتعكس التغيير فوراً في الواجهة (إذا لم يتم إعادة التوجيه فورًا)
            $this->currentLocale = $localeToSwitchTo;

            // 4. أعد توجيه المستخدم إلى نفس الصفحة التي كان عليها
            //    هذا سيؤدي إلى طلب جديد، حيث سيقوم الـ Middleware بتطبيق اللغة من الجلسة.
            return Redirect::to(request()->header('Referer', url()->current()));
        }
    }

    public function render()
    {
        // في كل مرة يتم فيها عرض المكون (render),
        // تأكد أن الخاصية currentLocale تعكس اللغة الفعلية للتطبيق حاليًا.
        // هذا مهم بشكل خاص بعد إعادة التوجيه.
        $this->currentLocale = App::getLocale();

        return view('livewire.language-switcher');
    }
}
