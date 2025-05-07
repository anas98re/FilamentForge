<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvitationGroup;
use App\Models\Invitation;
use App\Mail\PerformanceInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // For logging errors
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Exception; // For catching generic exceptions

class InvitationManager extends Component
{
    // Number of recent invitations to display per group
    protected const RECENT_INVITATIONS_LIMIT = 5;

    public $invitationGroups;
    public ?InvitationGroup $selectedGroup = null;
    public string $recipientEmail = '';
    public bool $showSendInvitationModal = false;

    /**
     * Mount the component.
     * Load initial data.
     */
    public function mount(): void
    {
        $this->loadInvitationGroups();
    }

    /**
     * Load invitation groups with their invitation counts and recent invitations.
     */
    public function loadInvitationGroups(): void
    {
        $this->invitationGroups = InvitationGroup::withCount('invitations')
            ->with(['invitations' => function ($query) {
                $query->latest()->take(self::RECENT_INVITATIONS_LIMIT);
            }])
            ->get();
    }

    /**
     * Open the send invitation modal for a specific group.
     * @param int $groupId The ID of the InvitationGroup.
     */
    public function openSendInvitationModal(int $groupId): void
    {
        $this->selectedGroup = InvitationGroup::find($groupId);

        if ($this->selectedGroup) {
            $this->recipientEmail = '';
            $this->resetErrorBag(['recipientEmail']); // Clear previous validation errors
            $this->showSendInvitationModal = true;
        } else {
            $this->notifyError(__('Error'), __('Invitation group not found.'));
        }
    }

    /**
     * Close the send invitation modal and reset its state.
     */
    public function closeSendInvitationModal(): void
    {
        $this->showSendInvitationModal = false;
        $this->selectedGroup = null;
        $this->recipientEmail = '';
        $this->resetErrorBag(['recipientEmail']);
    }

    /**
     * Validate input and orchestrate the invitation sending process.
     */
    public function sendInvitation(): void
    {
        if (!$this->selectedGroup) {
            $this->notifyError(__('Error'), __('No group selected or group not found.'));
            $this->closeSendInvitationModal();
            return;
        }

        $validatedData = $this->validate([
            'recipientEmail' => 'required|email|max:255', // Added max length
        ]);

        $currentLocale = app()->getLocale();
        $invitation = $this->_createPendingInvitation($validatedData['recipientEmail'], $currentLocale);

        if (!$invitation) {
            $this->notifyError(__('Error'), __('Could not create invitation record.'));
            $this->closeSendInvitationModal();
            return;
        }

        $emailSent = $this->_dispatchInvitationEmail($invitation, $currentLocale);
        $this->_handleEmailDispatchResult($invitation, $emailSent, $validatedData['recipientEmail']);

        $this->closeSendInvitationModal();
        $this->loadInvitationGroups(); // Refresh the list
    }

    /**
     * Create a new invitation record with a 'pending' status.
     * @param string $email Recipient's email.
     * @param string $locale Current application locale.
     * @return Invitation|null The created Invitation object or null on failure.
     */
    private function _createPendingInvitation(string $email, string $locale): ?Invitation
    {
        try {
            return Invitation::create([
                'invitation_group_id' => $this->selectedGroup->id,
                'email' => $email,
                'token' => Str::random(32),
                'status' => Invitation::STATUS_PENDING, // Assuming STATUS_PENDING constant exists in Invitation model
                'language_code' => $locale,
            ]);
        } catch (Exception $e) {
            Log::error("Failed to create invitation: " . $e->getMessage(), [
                'email' => $email,
                'group_id' => $this->selectedGroup->id,
            ]);
            return null;
        }
    }

    /**
     * Dispatch the invitation email.
     * @param Invitation $invitation The invitation record.
     * @param string $currentLocale The locale to send the email in.
     * @return bool True if email dispatch was attempted successfully (doesn't guarantee delivery), false otherwise.
     */
    private function _dispatchInvitationEmail(Invitation $invitation, string $currentLocale): bool
    {
        $recipientName = $currentLocale === 'ar' ? 'عزيزي المستخدم' : 'Dear User'; // Consider making this configurable or dynamic
        // IMPORTANT: Replace 'survey.show' with your actual survey route and ensure it's defined.
        $surveyLink = route('survey.show', ['token' => $invitation->token]);

        try {
            Mail::to($invitation->email)
                ->locale($currentLocale) // Crucial for the Mailable to use the correct language
                ->send(new PerformanceInvitationMail($invitation, $recipientName, $surveyLink, $currentLocale));
            return true;
        } catch (Exception $e) {
            Log::error('Failed to send invitation email for invitation ID ' . $invitation->id . ': ' . $e->getMessage(), [
                'email' => $invitation->email,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Handle the result of the email dispatch attempt.
     * Updates invitation status and sends notifications.
     * @param Invitation $invitation The invitation record.
     * @param bool $emailSent True if email dispatch was successful, false otherwise.
     * @param string $recipientEmail Recipient's email for notification messages.
     */
    private function _handleEmailDispatchResult(Invitation $invitation, bool $emailSent, string $recipientEmail): void
    {
        if ($emailSent) {
            $invitation->update([
                'status' => Invitation::STATUS_SENT, // Assuming STATUS_SENT constant
                'sent_at' => now()
            ]);
            $this->notifySuccess(
                __('Invitation Sent'),
                __('An invitation has been successfully sent to :email.', ['email' => $recipientEmail])
            );
        } else {
            $invitation->update(['status' => Invitation::STATUS_FAILED]); // Assuming STATUS_FAILED constant
            $this->notifyError(
                __('Error Sending Email'),
                __('Could not send invitation to :email. Please check logs for details.', ['email' => $recipientEmail])
            );
        }
    }

    /**
     * Helper method to send a success notification.
     * @param string $title
     * @param string $body
     */
    private function notifySuccess(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }

    /**
     * Helper method to send an error/danger notification.
     * @param string $title
     * @param string $body
     */
    private function notifyError(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->send();
    }

    /**
     * Render the component.
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.invitation-manager');
    }
}
