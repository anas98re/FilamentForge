<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_group_id',
        'email',
        'token',
        'status',
        'language_code',
        'sent_at',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function invitationGroup()
    {
        return $this->belongsTo(InvitationGroup::class);
    }

    // علاقة: الدعوة يمكن أن تكون مرسلة بواسطة مستخدم معين (اختياري، إذا أردت تتبع من أرسل الدعوة)
    // public function inviter(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'invited_by_user_id'); // ستحتاج لإضافة العمود invited_by_user_id في الـ migration
    // }
}
