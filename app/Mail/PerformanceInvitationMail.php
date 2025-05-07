<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;
// Implementing ShouldQueue is good practice for sending emails asynchronously
class PerformanceInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public string $recipientName; // Can be a generic "Dear User" or a specific name if known
    public string $surveyLink;
    public string $language; // The language code (e.g., 'en', 'ar') for the email content

    /**
     * Create a new message instance.
     *
     * @param Invitation $invitation The invitation model instance.
     * @param string $recipientName The name to address the recipient by.
     * @param string $surveyLink The URL to the survey.
     * @param string $language The language code for the email.
     */
    public function __construct(Invitation $invitation, string $recipientName, string $surveyLink, string $language)
    {
        $this->invitation = $invitation;
        $this->recipientName = $recipientName;
        $this->surveyLink = $surveyLink;
        $this->language = $language;
    }

    /**
     * Get the message envelope.
     * Defines the email subject and other envelope properties.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        // Determine the subject based on the language
        $subject = $this->language === 'ar'
            ? 'دعوة للمشاركة في استبيان تقييم الأداء' // Arabic Subject
            : 'Invitation to Participate in Performance Assessment Survey'; // English Subject

        return new Envelope(
            subject: $subject,
            // You can also set 'from', 'replyTo', etc. here if needed globally for this Mailable
            // e.g., from: new Address(config('mail.from.address'), config('mail.from.name')),
        );
    }

    /**
     * Get the message content definition.
     * Defines the view and data for the email body.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        // Construct the view name, potentially considering the language
        // (if you wanted separate view files per language, e.g., 'emails.performance.invitation_ar').
        // Currently, using a single view and relying on translation within that view.
        return new Content(
            markdown: 'emails.performance.invitation', // The Markdown view file for the email
            with: [ // Data to pass to the Markdown view
                'recipientName' => $this->recipientName,
                'surveyLink' => $this->surveyLink,
                'language' => $this->language, // Pass the language to the view for conditional translations
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment> An array of attachments.
     */
    public function attachments(): array
    {
        // Return any attachments if needed, e.g., PDF instructions
        return [];
    }
}
