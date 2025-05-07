<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation; // لاستخدامه إذا أردت تمرير كائن الدعوة كاملاً

class PerformanceInvitationMail extends Mailable implements ShouldQueue // Implementing ShouldQueue is good practice
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public string $recipientName; // يمكن أن يكون "عزيزي" أو اسم الشخص إذا كان معروفاً
    public string $surveyLink;
    public string $language;

    /**
     * Create a new message instance.
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
     */
    public function envelope(): Envelope
    {
        // تحديد الموضوع بناءً على اللغة
        $subject = $this->language === 'ar' ? 'دعوة للمشاركة في استبيان تقييم الأداء' : 'Invitation to Participate in Performance Assessment Survey';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // بناء اسم الـ view مع مراعاة اللغة (إذا أردت ملفات view منفصلة لكل لغة)
        // حالياً سنستخدم نفس الـ view ونعتمد على الترجمة داخل الـ view.
        return new Content(
            markdown: 'emails.performance.invitation', // اسم ملف الـ view
            with: [ // البيانات التي ستمرر إلى الـ view
                'recipientName' => $this->recipientName,
                'surveyLink' => $this->surveyLink,
                'language' => $this->language, // لتمرير اللغة إلى الـ view للترجمة
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
