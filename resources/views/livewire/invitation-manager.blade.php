<div>
    {{-- Display success or error messages from Session Flash  --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse ($invitationGroups as $group)
            <div class="p-6 bg-white shadow rounded-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"> {{-- Using standard Filament Card classes --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Sent Invitations') }}: {{ $group->invitations_count ?? 0 }}
                        </p>
                    </div>
                    {{-- Filament Button --}}
                    <x-filament::button wire:click="openSendInvitationModal({{ $group->id }})">
                        {{ __('Send Invitation') }}
                    </x-filament::button>
                </div>

                {{-- Section to display sent invitations for this group --}}
                <div class="mt-4">
                    @if ($group->invitations->isNotEmpty())
                        <ul class="divide-y divide-gray-200 dark:divide-white/10">
                            @foreach ($group->invitations as $inv)
                                <li class="py-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $inv->email }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $inv->status === 'sent',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' => $inv->status === 'pending',
                                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' => $inv->status === 'failed',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100' => $inv->status === 'completed',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' => !in_array($inv->status, ['sent', 'pending', 'failed', 'completed']),
                                            ])>
                                                {{ __(ucfirst($inv->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $inv->sent_at ? $inv->sent_at->translatedFormat('d M, Y H:i') : __('Not sent yet') }}
                                        ({{ $inv->language_code }})
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        @if ($group->invitations_count > $group->invitations->count())
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ __('Showing last :count invitations.', ['count' => $group->invitations->count()]) }}
                            </p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No invitations sent yet for this group.') }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-6 bg-white shadow rounded-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 text-center">
                <p class="text-gray-500 dark:text-gray-400">{{ __('No invitation groups found. Please add some groups first.') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Modal Dialog to send an invitation --}}
    @if ($showSendInvitationModal && $selectedGroup)
    {{-- z-index adjusted here to z-[1000] --}}
    <div class="fixed inset-0 z-[1000] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Modal background overlay --}}
            <div
                x-data
                x-show="$wire.showSendInvitationModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 transition-opacity" {{-- Using Filament classes for opacity --}}
                wire:click="closeSendInvitationModal"
                aria-hidden="true"
            ></div>

            {{-- This element is to trick the browser into centering the modal contents. --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

            {{-- Actual modal content --}}
            <div
                x-data
                x-show="$wire.showSendInvitationModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg transform overflow-hidden rounded-xl bg-white p-6 text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:align-middle" {{-- Using Filament Modal classes --}}
            >
                <div class="space-y-2">
                    <h3 class="text-xl font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                        {{ __('Send Invitation for') }} {{ $selectedGroup->name }}
                    </h3>
                    <div class="mt-4 space-y-1"> {{-- Reducing space between title and field --}}
                        <label for="recipientEmailModal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Recipient Email Address') }}</label>
                        <x-filament::input.wrapper> {{-- Using Filament input wrapper --}}
                            <x-filament::input
                                wire:model.defer="recipientEmail"
                                type="email"
                                name="recipientEmailModal"
                                id="recipientEmailModal"
                                placeholder="you@example.com"
                                required
                            />
                        </x-filament::input.wrapper>
                        @error('recipientEmail') <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p> @enderror {{-- Using Filament classes for error --}}
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 filament-modal-actions"> {{-- Using Filament classes for actions --}}
                    <x-filament::button
                        wire:click="sendInvitation"
                        wire:loading.attr="disabled"
                        wire:target="sendInvitation"
                        color="primary"
                    >
                        <x-filament::loading-indicator wire:loading wire:target="sendInvitation" class="h-5 w-5 mr-2"/>
                        {{ __('Send') }}
                    </x-filament::button>
                    <x-filament::button
                        wire:click="closeSendInvitationModal"
                        color="gray"
                        outlined
                    >
                        {{ __('Cancel') }}
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
