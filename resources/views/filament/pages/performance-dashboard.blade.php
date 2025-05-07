<x-filament-panels::page>
    {{-- مكون الألسنة من Filament --}}
    <x-filament::tabs>
        {{-- المرور على الألسنة المعرفة في ميثود getPageTabs() --}}
        @foreach ($this->getPageTabs() as $tabId => $tabConfig)
            <x-filament::tabs.item
                :active="$activeTab === $tabId"  {{-- تحديد ما إذا كان اللسان الحالي هو النشط --}}
                :icon="$tabConfig->getIcon()"  {{-- عرض الأيقونة المعرفة للسان --}}
                wire:click="$set('activeTab', '{{ $tabId }}')" {{-- عند النقر، تحديث خاصية activeTab --}}
                :badge="$tabConfig->getBadge()" {{-- عرض الشارة إذا كانت موجودة --}}
            >
                {{ $tabConfig->getLabel() }} {{-- عرض اسم اللسان --}}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    {{-- عرض المحتوى بناءً على اللسان النشط ($activeTab) --}}
    <div class="mt-6"> {{-- إضافة بعض التباعد العلوي --}}
        @if ($activeTab === 'invitations_management')
            <div>
                {{-- محتوى لسان "إدارة الدعوات" --}}
                {{-- في الخطوة التالية، سنضع هنا مكون Livewire @livewire('invitation-manager') --}}
                <h2 class="text-xl font-semibold mb-4">{{ __('Invitations Management') }}</h2>
                <p>{{ __('This is where the invitations management functionality will be implemented.') }}</p>
            </div>
        @elseif ($activeTab === '360_assessments')
            <div>
                {{-- محتوى لسان "تقييمات 360" --}}
                <h2 class="text-xl font-semibold mb-4">{{ __('360 Assessments') }}</h2>
                <p>{{ __('Content for 360 Assessments tab.') }}</p>
            </div>
        @elseif ($activeTab === 'view_360_assessments')
            <div>
                {{-- محتوى لسان "عرض تقييمات 360" --}}
                <h2 class="text-xl font-semibold mb-4">{{ __('View 360 Assessments') }}</h2>
                <p>{{ __('Content for View 360 Assessments tab.') }}</p>
            </div>
        @elseif ($activeTab === 'total_invitations_summary')
            <div>
                {{-- محتوى لسان "ملخص إجمالي الدعوات" --}}
                <h2 class="text-xl font-semibold mb-4">{{ __('Total Invitations Summary') }}</h2>
                <p>{{ __('Content for Total Invitations Summary tab.') }}</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
