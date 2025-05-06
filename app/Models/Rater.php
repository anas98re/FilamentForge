<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rater extends Model
{
    protected $fillable = [
        'assessment_id',
        'rater_group_id',
        'email',
        'invitation_status',
        'invitation_sent_at',
        'last_opened_at',
        'completed_at',
        'num_reminders_sent',
        'last_reminder_sent_at'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function group()
    {
        return $this->belongsTo(RaterGroup::class, 'rater_group_id');
    }
}
