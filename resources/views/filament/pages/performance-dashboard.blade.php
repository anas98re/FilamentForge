<x-filament-panels::page>
    {{-- مكون الألسنة من Filament --}}
    <x-filament::tabs>
        {{-- المرور على الألسنة المعرفة في ميثود getPageTabs() في كلاس الصفحة --}}
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
    <div class="mt-6"> {{-- إضافة بعض التباعد العلوي للمحتوى --}}

        {{-- محتوى لسان إدارة الدعوات --}}
        @if ($activeTab === 'invitations_management')
            <div>
                {{-- هنا يتم تضمين مكون Livewire لإدارة الدعوات --}}
                @livewire('invitation-manager')
            </div>
        @endif

        {{-- محتوى لسان تقييمات 360 --}}
        @if ($activeTab === '360_assessments')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('360 Assessments') }}</h2>
                <p>{{ __('Content for 360 Assessments tab.') }}</p>
                {{-- يمكنك إضافة المزيد من المحتوى أو مكونات Livewire أخرى هنا --}}
            </div>
        @endif

        {{-- محتوى لسان عرض تقييمات 360 --}}
        @if ($activeTab === 'view_360_assessments')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('View 360 Assessments') }}</h2>
                <p>{{ __('Content for View 360 Assessments tab.') }}</p>
                {{-- يمكنك إضافة المزيد من المحتوى أو مكونات Livewire أخرى هنا --}}
            </div>
        @endif

        {{-- محتوى لسان ملخص إجمالي الدعوات --}}
        @if ($activeTab === 'total_invitations_summary')
            <div>
                <h2 class="text-xl font-semibold mb-4">{{ __('Total Invitations Summary') }}</h2>
                <p>{{ __('Content for Total Invitations Summary tab.') }}</p>
                {{-- يمكنك إضافة المزيد من المحتوى أو مكونات Livewire أخرى هنا --}}
            </div>
        @endif
    </div>
</x-filament-panels::page>
