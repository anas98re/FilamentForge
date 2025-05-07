<x-filament-panels::page>
    {{-- Filament Tabs Component --}}
    <x-filament::tabs>
        {{-- Loop through tabs defined in the getPageTabs() method in the Page class --}}
        @foreach ($this->getPageTabs() as $tabId => $tabConfig)
            <x-filament::tabs.item
                :active="$activeTab === $tabId"  {{-- Determine if the current tab is active --}}
                :icon="$tabConfig->getIcon()"  {{-- Display the icon defined for the tab --}}
                wire:click="$set('activeTab', '{{ $tabId }}')" {{-- On click, update the activeTab property --}}
                :badge="$tabConfig->getBadge()" {{-- Display the badge if it exists --}}
            >
                {{ $tabConfig->getLabel() }} {{-- Display the tab label --}}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    {{-- Display content based on the active tab ($activeTab) --}}
    <div class="mt-6"> {{-- Add some top margin for the content --}}

        {{-- Invitations Management Tab Content --}}
        @if ($activeTab === 'invitations_management')
            <div>
                {{-- Livewire component for invitations management is included here --}}
                @livewire('invitation-manager')
            </div>
        @endif

        {{-- 360 Assessments Tab Content --}}
        @if ($activeTab === '360_assessments')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('360 Assessments') }}</h2>
                <p>{{ __('Content for 360 Assessments tab.') }}</p>
                {{-- You can add more content or other Livewire components here --}}
            </div>
        @endif

        {{-- View 360 Assessments Tab Content --}}
        @if ($activeTab === 'view_360_assessments')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('View 360 Assessments') }}</h2>
                <p>{{ __('Content for View 360 Assessments tab.') }}</p>
                {{-- You can add more content or other Livewire components here --}}
            </div>
        @endif

        {{-- Total Invitations Summary Tab Content --}}
        @if ($activeTab === 'total_invitations_summary')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('Total Invitations Summary') }}</h2>
                <p>{{ __('Content for Total Invitations Summary tab.') }}</p>
                {{-- You can add more content or other Livewire components here --}}
            </div>
        @endif
    </div>
</x-filament-panels::page>
