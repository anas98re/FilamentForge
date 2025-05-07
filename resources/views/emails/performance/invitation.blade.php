{{-- تحديد اتجاه النص بناءً على اللغة --}}
<x-mail::message :dir="$language === 'ar' ? 'rtl' : 'ltr'">

{{-- التحية --}}
@if ($language === 'ar')
مرحباً {{ $recipientName }},
@else
Hello {{ $recipientName }},
@endif

{{-- رسالة الدعوة --}}
@if ($language === 'ar')
هذه دعوة للمشاركة في استبيان ذكاء عاطفي BCA360.
@else
This is an invitation to participate in the BCA360 Emotional Intelligence survey.
@endif

{{-- رابط الاستبيان --}}
<x-mail::button :url="$surveyLink">
@if ($language === 'ar')
شارك في الاستبيان
@else
Participate in the Survey
@endif
</x-mail::button>

{{-- ملاحظة الدعم --}}
@if ($language === 'ar')
إذا كنت بحاجة إلى مساعدة، يرجى الاتصال بنا عبر البريد الإلكتروني على support@example.com.
@else
If you need assistance, please contact us via email at support@example.com.
@endif

{{-- الختام --}}
@if ($language === 'ar')
مع أطيب التحيات،
فريق تقنية المعلومات
@else
Best regards,
The IT Team
@endif

</x-mail::message>
