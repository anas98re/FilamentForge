<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvitationGroup;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use App\Mail\PerformanceInvitationMail;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
// use Illuminate\Support\Facades\Log; // إذا أردت تسجيل الأخطاء

class InvitationManager extends Component
{
    public $invitationGroups;
    public ?InvitationGroup $selectedGroup = null;
    public $recipientEmail = '';
    public $showSendInvitationModal = false;

    public function mount()
    {
        $this->loadInvitationGroups();
    }

    public function loadInvitationGroups()
    {
        $this->invitationGroups = InvitationGroup::withCount('invitations')
            ->with(['invitations' => function ($query) {
                $query->latest()->take(5);
            }])
            ->get();
    }

    public function openSendInvitationModal(int $groupId)
    {
        $this->selectedGroup = InvitationGroup::find($groupId);
        if ($this->selectedGroup) {
            $this->recipientEmail = '';
            $this->resetErrorBag(['recipientEmail']); // مسح أخطاء التحقق السابقة
            $this->showSendInvitationModal = true;
        } else {
            Notification::make()
                ->title(__('Error'))
                ->body(__('Invitation group not found.'))
                ->danger()
                ->send();
        }
    }

    public function closeSendInvitationModal()
    {
        $this->showSendInvitationModal = false;
        $this->selectedGroup = null;
        $this->recipientEmail = '';
        $this->resetErrorBag(['recipientEmail']);
    }

    public function sendInvitation()
    {
        if (!$this->selectedGroup) { // تحقق إضافي هنا
            Notification::make()
                ->title(__('Error'))
                ->body(__('No group selected or group not found.'))
                ->danger()
                ->send();
            $this->closeSendInvitationModal();
            return;
        }

        $validatedData = $this->validate([ // تخزين البيانات المتحقق منها
            'recipientEmail' => 'required|email',
        ]);

        $currentLocale = app()->getLocale(); // اللغة الحالية للتطبيق

        // 1. حفظ الدعوة في قاعدة البيانات
        $invitation = Invitation::create([
            'invitation_group_id' => $this->selectedGroup->id,
            'email' => $validatedData['recipientEmail'], // استخدام القيمة المتحقق منها
            'token' => Str::random(32),
            'status' => 'pending', // نبدأ بـ pending، ثم نغيرها إلى sent أو failed
            'language_code' => $currentLocale,
            // 'sent_at' => now(), // سيتم تعيينه بعد الإرسال الناجح
            // 'expires_at' => now()->addDays(7),
        ]);

        // 2. إرسال البريد الإلكتروني
        $recipientName = $currentLocale === 'ar' ? 'عزيزي المستخدم' : 'Dear User'; // *** التصحيح هنا ***

        // سنستخدم رابطًا وهميًا للاستبيان حاليًا
        // تأكد من أن لديك route باسم 'home' أو غيّره
        $surveyLink = route('survey.show', ['token' => $invitation->token]);


        try {
            Mail::to($validatedData['recipientEmail'])
                ->locale($currentLocale) // مهم لـ Mailable
                ->send(new PerformanceInvitationMail($invitation, $recipientName, $surveyLink, $currentLocale));

            // تحديث حالة الدعوة إلى مرسلة وتسجيل وقت الإرسال
            $invitation->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Notification::make()
                ->title(__('Invitation Sent'))
                ->body(__('An invitation has been successfully sent to :email.', ['email' => $validatedData['recipientEmail']]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            $invitation->update(['status' => 'failed']);

            Notification::make()
                ->title(__('Error Sending Email'))
                ->body(__('Could not send invitation to :email. Error: :message', ['email' => $validatedData['recipientEmail'], 'message' => $e->getMessage()]))
                ->danger()
                ->send();
            // Log::error('Failed to send invitation email to ' . $validatedData['recipientEmail'] . ': ' . $e->getMessage());
        }

        $this->closeSendInvitationModal();
        $this->loadInvitationGroups();
        // $this->dispatch('invitationSent'); // إذا كنت ستستخدم هذا الحدث
    }

    public function render()
    {
        return view('livewire.invitation-manager');
    }
}
