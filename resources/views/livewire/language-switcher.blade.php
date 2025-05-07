<div class="relative fi-dropdown fi-user-menu">
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <button
                type="button"
                class="flex items-center justify-center w-10 h-10 font-semibold text-sm text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700"
                aria-label="{{ __('Switch Language') }}"
            >
                {{ strtoupper($currentLocale) }}
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach ($supportedLocales as $localeCode => $properties)
                <x-filament::dropdown.list.item
                    wire:click="switchLocale('{{ $localeCode }}')"
                    tag="button"
                    class="{{ $currentLocale === $localeCode ? 'font-bold bg-gray-100 dark:bg-gray-700' : '' }}"
                >
                    {{ is_array($properties) ? $properties['name'] : $properties }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
