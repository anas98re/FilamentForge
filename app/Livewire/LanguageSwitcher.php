<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\View\View;     

class LanguageSwitcher extends Component
{
    public string $currentLocale;
    public array $supportedLocales;

    /**
     * Mount the component.
     * Initializes the current locale and supported locales.
     */
    public function mount(): void
    {
        // On initial component load, read the locale from the session.
        // If not found in the session, use the default locale from the application config.
        $this->currentLocale = Session::get('locale', config('app.locale'));

        // Load supported locales from the configuration file.
        // Provide a default array if not found in config, though it should be there.
        $this->supportedLocales = config('app.supported_locales', [
            'en' => 'English',
            'ar' => 'العربية',
        ]);
    }

    /**
     * Switch the application's locale.
     *
     * @param string $localeToSwitchTo The locale code to switch to (e.g., 'en', 'ar').
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function switchLocale(string $localeToSwitchTo)
    {
        // Check if the requested locale is within the supported locales.
        if (array_key_exists($localeToSwitchTo, $this->supportedLocales)) {

            // 1. Put the new locale in the session to be used for subsequent requests.
            Session::put('locale', $localeToSwitchTo);

            // 2. Immediately change the application's locale for the current request (optional but good practice).
            //    This helps if any logic depends on the locale before the redirect.
            App::setLocale($localeToSwitchTo);

            // 3. Update the currentLocale property in this component to reflect the change immediately
            //    in the UI (if it doesn't redirect instantly or if UI updates before redirect).
            $this->currentLocale = $localeToSwitchTo;

            // 4. Redirect the user back to the page they were on.
            //    This will trigger a new request, where the SetLocale middleware
            //    will apply the locale from the session.
            return Redirect::to(request()->header('Referer', url()->current()));
        }
        // Optionally, handle the case where the locale is not supported,
        // though the UI should only present supported locales.
        return null;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): View
    {
        // Each time the component is rendered,
        // ensure the currentLocale property accurately reflects the application's actual current locale.
        // This is especially important after a redirect.
        $this->currentLocale = App::getLocale();

        return view('livewire.language-switcher');
    }
}
